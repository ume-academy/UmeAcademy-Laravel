<?php

namespace App\Services\Auth;

use App\Repositories\User\UserRepository;

class RegisterUserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register($data)
    {
        return $this->userRepository->register($data);
    }
}
