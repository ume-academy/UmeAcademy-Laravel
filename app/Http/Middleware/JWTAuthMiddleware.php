<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JWTAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = JWTAuth::getToken();

        if (!$token) {
            return response()->json(['error' => 'Token không được cung cấp.'], 401);
        }

        try {
            JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token đã hết hạn.'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token không hợp lệ.'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Có lỗi xảy ra với token.'], 401);
        }

        return $next($request);
    }
}
