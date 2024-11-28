<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Notifications\ResetPasswordNotification;
use App\Http\Requests\Auth\ForgotPasswordRequest;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        try {
            // Tìm người dùng theo email
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json(['message' => 'Email không tồn tại trong hệ thống.'], 400);
            }

            // Tạo token reset mật khẩu
            $token = Password::getRepository()->create($user);

            // Gửi notification reset mật khẩu với token và email người dùng
            $user->notify(new ResetPasswordNotification($token, $request->email));

            return response()->json(['message' => 'Đã gửi email reset mật khẩu.'], 200);
        } catch(\Exception $e) {

            return response()->json([
                'message' => 'Đã có lỗi xảy ra trong quá trình gửi email.' . $e->getMessage(),
            ], 500);
        }
        
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        // Reset mật khẩu
        $response = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        // Kiểm tra phản hồi của reset mật khẩu
        if ($response == Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Mật khẩu đã được thay đổi thành công.',
            ], 200);
        }

        return response()->json([
            'message' => 'Đã có lỗi xảy ra khi đặt lại mật khẩu.',
        ], 500);
    }
}
