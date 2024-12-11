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

use Illuminate\Console\Command;

class RefundController extends Controller
{
    public function __construct(
        private RefundService $refundService
    ){}

    public function getAllRefundRequest(Request $req) {
        try {
            $perPage = $req->input('per_page', 10);

            $transactions = $this->refundService->getAllRefundRequest($perPage);
            return RefundResource::collection($transactions);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $req, $id) {
        try {
            $status = $req->input('status');
            
            $request = $this->refundService->updateStatus($id, $status);
            return new RefundResource($request);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

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
            if ($transaction->created_at->diffInDays(now()) > 7) {
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

            // Lấy hành động từ tham số trong request
            $status = $request->input('status'); 

            if ($status == 1) {
                $refund->status = 1;
                $refund->save();
                return response()->json(['message' => 'Yêu cầu hoàn tiền đã được chấp nhận.']);
            } elseif ($status == 0) {
                $refund->status = 0;
                $refund->save();
                return response()->json(['message' => 'Yêu cầu hoàn tiền đã bị từ chối.']);
            } else {
                return response()->json(['error' => 'Status không hợp lệ'], 400);
            }

        } catch (\Exception $e) {
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
            return; 
        }

        foreach ($pendingRefunds as $refund) {
            $transaction = $refund->transaction;
            
            // Hoàn tiền cho học viên
            $wallet = StudentWallet::firstOrCreate(['user_id' => $transaction->user_id]);
            $wallet->increment('balance', $transaction->discount_price);

            // Cập nhật trạng thái
            $refund->update(['status' => 1]); // completed
            $transaction->update(['status' => 'refunded']);
        }
    }

}
