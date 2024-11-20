<?php

namespace App\Repositories\Interfaces;

interface TokenRepositoryInterface
{
    public function storeRefreshToken(int $userId, string $refreshToken);
    public function getRefreshToken(int $userId);
    public function deleteRefreshToken(string $refreshToken);
}
