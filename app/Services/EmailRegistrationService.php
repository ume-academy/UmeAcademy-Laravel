<?php

namespace App\Services;

use App\Services\Email\EmailService;
use App\Repositories\UserRepository;
use App\Contracts\EmailSenderInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;

class EmailRegistrationService
{
    private UserRepositoryInterface $userRepository;
    private EmailSenderInterface $emailSender;

    public function __construct()
    {
        $this->userRepository = new UserRepository(); 
        $this->emailSender = new EmailService(); 
    }

    public function register(array $data)
    {
        $user = $this->userRepository->create($data);

        // Gửi email xác thực
        $this->emailSender->sendVerificationEmail($user);

        return $user;
    }
}
