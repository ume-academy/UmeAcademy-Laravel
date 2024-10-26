<?php

namespace App\Contracts;

interface RefreshTokenInterface
{
    public function createRefreshToken(object $user);
    // public function refreshToken(object $user);
}
