<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\WithdrawMethod;
use App\Models\WithdrawalRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherWallet;

class WithdrawRequestController extends Controller
{
    public function create(Request $request)
    {
        // Xác định teacher hiện tại
        $user = JWTAuth::parseToken()->authenticate();
        $teacher = Teacher::where("user_id", $user->id)->first();
        
        // Kiểm tra phương thức thanh toán
        $paymentMethod = WithdrawMethod::where('teacher_id', $teacher->id)->first();
        // return($paymentMethod);

        if (!$paymentMethod) {
            return response()->json([
                'error' => 'Bạn chưa thêm phương thức thanh toán. Vui lòng thêm trước khi yêu cầu rút tiền.'
            ], 400);
        }

        // Validate dữ liệu yêu cầu
        $request->validate([
            'money' => 'required|numeric|min:1000', // Min rút: 1000
            // 'note' => 'nullable|string|max:255'
        ]);

        // Kiểm tra số dư 
        $available_balance = TeacherWallet::where('teacher_id', $teacher->id)->pluck('available_balance')->first();
        if ($available_balance < $request->input('money')) {
            return response()->json([
                'error'=> 'Số dư không đủ',
            ]);
        }

        // tru tien cua teacher
        $walletTeacher = TeacherWallet::where('teacher_id', $teacher->id)->first();
        $walletTeacher->available_balance = (int)$available_balance - (int)$request->input('money');
        $walletTeacher->save();

        // Tạo yêu cầu rút tiền
        $withdrawRequest = WithdrawalRequest::create([
            'teacher_id' => $teacher->id,
            'money' => $request->input('money'),
            'status' => 2, // pending
            // 'note' => $request->input('note'),
        ]);

        return response()->json([
            'message' => 'Yêu cầu rút tiền đã được tạo thành công.',
            'data' => $withdrawRequest
        ], 201);
    }

    public function update(Request $request, int $id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        // Kiểm tra quyền admin (bạn có thể dùng role-based middleware ở đây)
        // if ($user->role !== 'admin') {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Bạn không có quyền thực hiện hành động này.'
        //     ], 403);
        // }

        // Lấy yêu cầu rút tiền
        $withdrawRequest = WithdrawalRequest::find($id);

        if (!$withdrawRequest) {
            return response()->json([
                'status' => 'error',
                'message' => 'Yêu cầu rút tiền không tồn tại.'
            ], 404);
        }

        // Xác thực dữ liệu
        $request->validate([
            'status' => 'required|in:0,1',
            // 'note' => 'nullable|string|max:255',
        ]);

        // Cập nhật trạng thái yêu cầu rút tiền
        $withdrawRequest->status = $request->status;
        // $withdrawRequest->note = $request->note;
        $withdrawRequest->save();

        // Nếu yêu cầu được duyệt, bạn có thể thực hiện thanh toán (ví dụ: cập nhật tài khoản teacher)
        

        return response()->json([
            'status' => 'success',
            'message' => 'Yêu cầu rút tiền đã được cập nhật.',
            'data' => $withdrawRequest
        ], 200);
    
    }

    public function history()
    {
        // Xác định teacher hiện tại
        $user = JWTAuth::parseToken()->authenticate();
        $teacher = Teacher::where("user_id", $user->id)->first();

        $histories = WithdrawalRequest::where("teacher_id", $teacher->id)->get();
        return response()->json(['data' => $histories]);
    }
}