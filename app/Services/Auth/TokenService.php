<?php

namespace App\Services\Auth;

use App\Contracts\TokenInterface;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Contracts\RefreshTokenInterface;

class TokenService implements TokenInterface
{
    public function __construct(
        private RefreshTokenInterface $refreshTokenService
    ) {}

    public function createToken(array $credentials)
    {
        try {
            return JWTAuth::attempt($credentials);
        } catch (\Throwable $e) {
            // Xử lý lỗi ở đây, ví dụ: log lỗi
            return null;
        }
    }

    public function respondWithToken($token)
    {
        $refreshToken = $this->refreshTokenService->createRefreshToken(JWTAuth::user());

        return [
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60, 
        ];
    }
}
