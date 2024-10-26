<?php

namespace App\Services\Auth;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Contracts\RefreshTokenInterface;
use App\Repositories\Interfaces\RefreshTokenRepositoryInterface;

class RefreshTokenService implements RefreshTokenInterface
{
    public function __construct(
        private RefreshTokenRepositoryInterface $refreshTokenRepo
    ) {}

    public function createRefreshToken($user)
    {
        $refreshTokenTTL = time() + config('jwt.refresh_ttl') * 60;

        // Tạo refresh token
        $refreshToken = JWTAuth::claims(['exp' => $refreshTokenTTL])->fromUser($user);

        $this->refreshTokenRepo->updateOrCreate(
            ['user_id' => $user->id],
            [
                'token' => $refreshToken,
                'expires_at' => now()->addMinutes(config('jwt.refresh_ttl'))
            ], 
        );

        return $refreshToken;
    }

    // public function refreshToken($user)
    // {
    //     return [
    //         'access_token' => JWTAuth::refresh(JWTAuth::getToken()),
    //         'refresh_token' => $this->createRefreshToken($user)
    //     ];
    // }
}
