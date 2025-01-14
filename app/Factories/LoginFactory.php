<?php
namespace App\Factories;

use App\Services\EmaiLoginService;

class LoginFactory
{
    public function createLoginMethod(string $type)
    {
        switch ($type) {
            case 'email':
                return new EmaiLoginService();
            case 'google':
                // return new GoogleRegistrationHandler();
            case 'facebook':
                // return new FacebookRegistrationHandler();
            default:
                throw new \Exception("Phương thức đăng nhập không hợp lệ: $type");
                // Thêm app/Exception
        }
    }
}