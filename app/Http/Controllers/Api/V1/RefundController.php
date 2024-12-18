<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Refund\RefundResource;
use App\Services\RefundService;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\RefundRequest;
use App\Models\StudentWallet;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RefundController extends Controller
{
    // public function __construct(
    //     private RefundService $refundService
    // ){}

    // public function getAllRefundRequest(Request $req) {
    //     try {
    //         $perPage = $req->input('per_page', 10);

    //         $transactions = $this->refundService->getAllRefundRequest($perPage);
    //         return RefundResource::collection($transactions);

    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    // public function updateStatus(Request $req, $id) {
    //     try {
    //         $status = $req->input('status');
            
    //         $request = $this->refundService->updateStatus($id, $status);
    //         return new RefundResource($request);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    // Create a refund request
    public function createRefundRequest(Request $request, $transactionCode) 
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            // Lấy thông tin giao dịch
            $transaction = Transaction::where('transaction_code', $transactionCode)
                ->where('user_id', $user->id) // Chỉ cho phép người dùng sở hữu giao dịch
                ->first();

            // 
            if (!$transaction) {
                return response()->json(['error'=> 'Không tìm thấy giao dịch của người dùng này.'],404);
            }

            // Kiểm tra trạng thái giao dịch
            if ($transaction->status !== 'success') {
                return response()->json([
                    'message' => 'Giao dịch không đủ điều kiện để được hoàn lại tiền.',
                ], 400);
            }

            // Kiểm tra thời gian giao dịch
            if ($transaction->created_at < now()->subDays(7)) {
                return response()->json([
                    'message' => 'Thời hạn yêu cầu hoàn tiền đã hết.',
                ], 400);
            }

            // Kiểm tra nếu đã tạo yêu cầu hoàn tiền trước đó
            if (RefundRequest::where('transaction_code', $transaction->transaction_code)->exists()) {
                return response()->json([
                    'message' => 'Yêu cầu hoàn lại tiền đã tồn tại cho giao dịch này.',
                ], 400);
            }

            // Tạo yêu cầu hoàn tiền
            RefundRequest::create([
                'transaction_code' => $transactionCode,
                'status' => 2,
            ]);

            // Cập nhật trạng thái giao dịch
            $transaction->update(['status' => 2]);

            return response()->json([
                'message' => 'Yêu cầu hoàn tiền được tạo thành công.',
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error'=> $e->getMessage()], 500);
        }
    }

    // xét duyệt yêu cầu hoàn tiền
    public function reviewRefundRequest($transactionCode, Request $request)
    {
        try {
            
            $refund = RefundRequest::where('transaction_code', $transactionCode)->first();

            if (empty($refund)) {
                return response()->json(['error'=> 'Không tìm thấy giao dịch nào.'],404);
            }

            // Lấy hành động từ tham số trong request
            $status = $request->input('status'); 
            // if ($status == '' || $status == null) {
            //     return response()->json(['error'=> 'Status không được cung cấp.'],400);
            // }

            if ($status == 1) { // Chap nhan yeu cau hoan tien
                // dd($refund);
                
                DB::beginTransaction();
                $transaction = $refund->transaction;

                // Trừ tiền từ số dư tạm thời trong ví của giảng viên
                // $walletTeacher = TeacherWallet::where('teacher_id', $transaction->transaction_code)->first();
                // $walletTeacher->decrement('available_balance', $transaction->discount_price);

                // Lấy giao dịch
                // $transaction = Transaction::findOrFail($transactionId);

                // Lấy khóa học liên quan đến giao dịch
                $course = $transaction->course;

                if (!$course) {
                    throw new \Exception('Khóa học không tồn tại cho giao dịch này.');
                }

                // Lấy giáo viên từ khóa học
                $teacher = $course->teacher;

                if (!$teacher) {
                    throw new \Exception('Không tìm thấy giáo viên liên quan.');
                }

                // Lấy ví của giáo viên 
                $teacherWallet = $teacher->teacherWallet;
                // return $teacherWallet;

                if (!$teacherWallet) {
                    throw new \Exception('Không tìm thấy ví của giáo viên.');
                }

                // Số tiền hoàn
                $refundAmount = $transaction->discount_price;

                if ($teacherWallet->temporary_balance < $refundAmount) {
                    throw new \Exception('Số dư ví của giáo viên không đủ để hoàn tiền.');
                }

                // Trừ tiền trong ví tạm thời của giáo viên
                $teacherWallet->temporary_balance -= $refundAmount;
                $teacherWallet->save();

                // Cập nhật trạng thái giao dịch
                $transaction->status = 'refunded';
                $transaction->save();


                // Hoàn tiền cho học viên
                $wallet = StudentWallet::firstOrCreate(['user_id' => $transaction->user_id]);
                $wallet->increment('balance', $refundAmount);

                $refund->status = 1;
                $refund->save();
                DB::commit();
                return response()->json(['message' => 'Yêu cầu hoàn tiền đã được chấp nhận.']);
            } elseif ($status == 0) {
                $refund->status = 0;
                $refund->save();
                return response()->json(['message' => 'Yêu cầu hoàn tiền đã bị từ chối.']);
            } else {
                return response()->json(['error' => 'Status không hợp lệ'], 400);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error'=> $e->getMessage()], 500);
        }
    }

    public function processPendingRefunds()
    {
        $pendingRefunds = RefundRequest::where('status', 2)
            ->where('created_at', '<', now()->subDays(3))
            ->get();

        // Kiểm tra nếu có yêu cầu hoàn tiền
        if ($pendingRefunds->isEmpty()) {
            Log::warning('Không có yêu cầu hoàn tiền');; 
        }

        foreach ($pendingRefunds as $refund) {
            try {
            DB::beginTransaction();
            $transaction = $refund->transaction;

            // Lấy khóa học liên quan đến giao dịch
            $course = $transaction->course;

            if (!$course) {
                Log::warning('Khóa học không tồn tại cho giao dịch này.');
            }

            // Lấy giáo viên từ khóa học
            $teacher = $course->teacher;

            if (!$teacher) {
                Log::warning('Không tìm thấy giáo viên liên quan.');
            }

            // Lấy ví của giáo viên 
            $teacherWallet = $teacher->teacherWallet;
            // return $teacherWallet;

            if (!$teacherWallet) {
                Log::warning('Không tìm thấy ví của giáo viên.');
            }

            // Số tiền hoàn
            $refundAmount = $transaction->discount_price;

            if ($teacherWallet->temporary_balance < $refundAmount) {
                Log::warning('Số dư ví của giáo viên không đủ để hoàn tiền.');
            }

            // Trừ tiền trong ví tạm thời của giáo viên
            $teacherWallet->temporary_balance -= $refundAmount;
            $teacherWallet->save();

            // Hoàn tiền cho học viên
            $wallet = StudentWallet::firstOrCreate(['user_id' => $transaction->user_id]);
            $wallet->increment('balance', $refundAmount);

            // Cập nhật trạng thái giao dịch
            $transaction->status = 'refunded';
            $transaction->save();

            $refund->status = 1;
            $refund->save();
            DB::commit();
            Log::warning('OK');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::warning($e->getMessage());
        }
        }
    }
// select những KH có trạng thái 'success' và không có yêu cầu hoàn tiền
// course_id => teacher_id
}
