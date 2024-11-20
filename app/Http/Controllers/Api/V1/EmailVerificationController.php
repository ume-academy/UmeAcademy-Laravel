<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResendEmailRequest;
use Illuminate\Support\Facades\Cache;

class EmailVerificationController extends Controller
{
    public function verify($id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Liên kết xác minh không hợp lệ.'], 400);
        }

        // Kiểm tra Email đã được xác minh chưa?
        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email đã được xác minh.'], 200);
        }

        // Đánh dấu email đã được xác minh trong Database
        $user->markEmailAsVerified();

        return response()->json(['message' => 'Email đã được xác minh thành công.'], 200);
    }

    public function resend(ResendEmailRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Không tìm thấy email.'], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email đã được xác minh.'], 200);
        }

        // Kiểm tra thời gian để gửi lại email
        if (!$this->canResendVerificationEmail($user)) {
            $remainingTime = $this->getRemainingTime($user);
            return response()->json([
                'message' => 'Vui lòng chờ ' . $remainingTime . ' giây trước khi yêu cầu email xác minh mới.'
            ], 429);
        }

        $user->sendEmailVerificationNotification();

        // Lưu thời gian gửi email vào cache
        Cache::put('email_verification_' . $user->id, now()->addMinutes(1)->timestamp, 60);

        return response()->json(['message' => 'Đã gửi lại email xác minh.'], 200);
    }

    // Kiểm tra thời gian gửi lại Email xác thực
    private function canResendVerificationEmail($user)
    {
        return !Cache::has('email_verification_' . $user->id);
    }

    // Lấy thời gian còn lại
    private function getRemainingTime($user)
    {
        $cacheKey = 'email_verification_' . $user->id;
        return Cache::get($cacheKey) - now()->timestamp;
    }
}
