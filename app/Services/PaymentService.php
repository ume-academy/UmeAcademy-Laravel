<?php

namespace App\Services;

use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\FeePlatformRepositoryInterface;
use App\Repositories\Interfaces\TeacherWalletRepositoryInterface;
use App\Repositories\Interfaces\TeacherWalletTransactionRepositoryInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\Interfaces\VoucherRepositoryInterface;
use App\Repositories\Interfaces\VoucherUsageRepositoryInterface;
use App\Traits\VoucherTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class PaymentService
{
    use VoucherTrait;
    private const PAYMENT_METHOD_BANKING = 2;

    public function __construct(
        private PayOSService $payOSService,
        private CourseRepositoryInterface $courseRepo,
        private VoucherRepositoryInterface $voucherRepo,
        private FeePlatformRepositoryInterface $feeRepo,
        private TransactionRepositoryInterface $transactionRepo,
        private VoucherUsageRepositoryInterface $voucherUsageRepo,
        private TeacherWalletRepositoryInterface $teacherWalletRepo,
        private TeacherWalletTransactionRepositoryInterface $teacherWalletTransactionRepo,
    ){}

    public function checkout($data) {
        $data = $this->formatCheckoutData($data);

        if($data['payment_method_id'] == self::PAYMENT_METHOD_BANKING) {
            $response = $this->payOSService->checkout($data);

            if($response) {
                $this->saveData($response, $data);
            }

            return $response;
        } else {
            return 'VI UME';
        }
    }

    public function confirmWebhook($data) {
        if(isset($data['success']) && $data['success'] == true) {
            DB::beginTransaction();
            try {
                // Transaction
                $transaction = $this->transactionRepo->getByCode($data['data']['orderCode']);
                $this->transactionRepo->updateStatus($transaction->id, 'success');

                // Voucher usage
                $voucherUsage = $this->voucherUsageRepo->getByTransaction($transaction->id);
                if($voucherUsage) {
                    $this->voucherUsageRepo->updateStatus($voucherUsage->id, 1);
                }
                
                // Course enrolled
                $course = $this->courseRepo->getById($transaction->course_id);
                $this->courseRepo->syncCourseEnrolled($course, [$transaction->user_id]);

                // Teacher wallet
                $teacherWallet = $this->teacherWalletRepo->getByTeacher($course->teacher_id);

                $dataTeacherWallet = [
                    'available_balance' => intval($teacherWallet->available_balance) + intval($transaction->revenue_teacher),
                    'total_earnings' => intval($teacherWallet->total_earnings) + intval($transaction->revenue_teacher)
                ];
                $this->teacherWalletRepo->update($course->teacher_id, $dataTeacherWallet);

                // Transaction wallet teacher
                $dataTeacherWalletTransaction = [
                    'code' => $this->generateTransactionCode(),
                    'type' => 'available_receive_money',
                    'balance_tracking' => $transaction->revenue_teacher,
                    'teacher_wallet_id' => $teacherWallet->id
                ];
                $this->teacherWalletTransactionRepo->create($dataTeacherWalletTransaction);

                DB::commit();
                return 'success';
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => $e->getMessage()], 500);
            }
            
        } else {   
            return 'cancelled';
        }
    }

    public function cancel($data) {
        // Transaction
        $transaction = $this->transactionRepo->getByCode($data['orderCode']);
        $this->transactionRepo->updateStatus($transaction->id, 'canceled');

        // Voucher usage 
        $voucherUsage = $this->voucherUsageRepo->getByTransaction($transaction->id);
        if($voucherUsage) {
            $this->voucherUsageRepo->delete($voucherUsage->id);
        }
    }


    private function formatCheckoutData($data) {
        $user = JWTAuth::parseToken()->authenticate();
        $data['user_id'] = $user->id;

        $course = $this->courseRepo->getById($data['course_id']);
        $data['fee_platform'] = $this->feeRepo->getFeeTeacher($course->teacher_id);

        if ($course->checkEnrolled($user->id)) {
            throw new \Exception('Bạn đã mua khóa học này rồi!');
        }

        if (isset($data['voucher_id'])) {
            $voucher = $this->voucherRepo->find($data['voucher_id']);
            $voucher = $this->check($voucher, $course);
    
            $data['discount_price'] = $data['origin_price'] * (1 - $voucher->discount / 100);
            $data['revenue_teacher'] = $voucher->teacher
                                        ? $data['discount_price'] * (1 - $data['fee_platform'] / 100)
                                        : $data['origin_price'] * (1 - $data['fee_platform'] / 100);
        } else {
            $data['discount_price'] = $data['origin_price'];
            $data['revenue_teacher'] = $data['origin_price'] * (1 - $data['fee_platform'] / 100);
        }
    
        return $data;
    }

    private function saveData($response, $data) {
        $transactionData = [
            'transaction_code' => $response['orderCode'],
            'origin_price' => $data['origin_price'],
            'discount_price' => $data['discount_price'],
            'revenue_teacher' => $data['revenue_teacher'],
            'fee_platform' => $data['fee_platform'],
            'user_id' => $data['user_id'],
            'course_id' => $data['course_id'],
            'payment_method_id' => $data['payment_method_id'],
            'status' => $response['status']
        ];
        
        $transaction = $this->transactionRepo->create($transactionData);

        if(isset($data['voucher_id'])) {
            $voucherUsageData = [
                'user_id' => $data['user_id'],
                'transaction_id' => $transaction->id,
                'voucher_id' => $data['voucher_id'] ?? null,
                'used_at' => now()->toDateTimeString()
            ];
    
            $voucherUsage = $this->voucherUsageRepo->create($voucherUsageData);
        }
    }

    private function generateTransactionCode()
    {
        do {
            $code = Str::random(10);
        } while (DB::table('transactions')->where('transaction_code', $code)->exists());

        return $code;
    }
}
