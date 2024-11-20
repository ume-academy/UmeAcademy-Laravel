<?php
namespace App\Factories;

use App\Services\EmailRegistrationService;

class RegistrationFactory
{
    public function createRegistrationMethod(string $type)
    {
        switch ($type) {
            case 'email':
                return new EmailRegistrationService();
            // case 'google':
                // đăng ký bằng Google
            default:
                throw new \Exception("Phương thức đăng ký không hợp lệ: $type");
        }
    }
}