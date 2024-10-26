<?php

namespace App\Services\Auth;

use Tymon\JWTAuth\Facades\JWTAuth;

class LogoutServiceService
{
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return ['message' => 'Đăng xuất thành công.'];
    }
}
