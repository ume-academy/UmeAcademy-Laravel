<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\WithdrawMethod;
use App\Models\WithdrawalRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class WithdrawRequestController extends Controller
{
    public function create(Request $request)
    {
        // Xác định teacher hiện tại
        $user = JWTAuth::parseToken()->authenticate();
        $teacher = $user->teacher();
        return $teacher;

        // Kiểm tra phương thức thanh toán
        $paymentMethod = WithdrawMethod::where('teacher_id', $teacher->id)->first();

        if (!$paymentMethod) {
            return response()->json([
                'error' => 'Bạn chưa thêm phương thức thanh toán. Vui lòng thêm trước khi yêu cầu rút tiền.'
            ], 400);
        }

        // Validate dữ liệu yêu cầu
        $request->validate([
            'amount' => 'required|numeric|min:1000', // Ví dụ: Số tiền tối thiểu là 1000
            'note' => 'nullable|string|max:255'
        ]);

        // Tạo yêu cầu rút tiền
        $withdrawRequest = WithdrawalRequest::create([
            'teacher_id' => $teacher->id,
            'amount' => $request->input('amount'),
            'status' => 'pending',
            'note' => $request->input('note'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Yêu cầu rút tiền đã được tạo thành công.',
            'data' => $withdrawRequest
        ], 201);
    }
}
