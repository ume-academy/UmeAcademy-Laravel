<?php

namespace App\Contracts;

interface TokenServiceInterface
{
    // public function createToken(array $credentials);
    // public function respondWithToken(string $token);
    // public function createRefreshToken(object $user);
    // public function validateToken(string $token): bool;
    // public function getTokenPayload(string $token): ?array;
    // public function invalidateToken(string $token): void;


    public function generateTokens(object $user);
    public function refreshTokens(string $refreshToken);
    public function revokeTokens(string $refreshToken);
}
