<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class VerifyJWTToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Lấy access_token từ Authorization header
        $token = JWTAuth::getToken();

        if (!$token) {
            return response()->json(['error' => 'Token không được cung cấp.'], 401);
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            /* 
                Server sẽ thực hiện việc trả về AT và RT sau khi phát hiện AT hết hạn
            */
            // Lấy refresh_token từ Cookies
            // $refreshToken = $request->cookie('refresh_token');

            // if (!$refreshToken) {
            //     return response()->json(['error' => 'Không tìm thấy refresh token nào. Vui lòng đăng nhập lại.'], 401);
            // }

            // Gọi đến method refreshToken của TokenService

            return response()->json(['error' => 'Token đã hết hạn.'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token không hợp lệ.'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Có lỗi xảy ra với token.'], 401);
        }

        // Kiểm tra xem email của người dùng đã được xác minh chưa?
        if (!$user->hasVerifiedEmail()) {
            return response()->json(['error' => 'Email chưa được xác minh.'], 403);
        }

        return $next($request);
    }
}
