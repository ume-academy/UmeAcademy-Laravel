<?php

namespace App\Services\Auth;

use App\Contracts\EmailSenderInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Contracts\RegistrationInterface;

class RegistrationViaEmailService 
{
    public function __construct(
        private UserRepositoryInterface $userRepository, 
        private EmailSenderInterface $emailSender, 
    ) {}

    public function registerViaEmail(array $data)
    {
        $user = $this->userRepository->create($data);

        // Gửi email xác thực
        $this->emailSender->sendVerificationEmail($user);

        return $user;
    }
}
