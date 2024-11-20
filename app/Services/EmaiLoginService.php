<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Interfaces\UserRepositoryInterface;

class EmaiLoginService
{
    private UserRepositoryInterface $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository(); 
    }

    public function login(array $credentials)
    {
        // Tìm người dùng theo email
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new \Exception('Tài khoản hoặc mật khẩu không chính xác.');
        }

        // Kiểm tra xem email đã xác minh chưa
        if (!$user->hasVerifiedEmail()) {
            throw new \Exception('Email chưa được xác minh.');
        }

        return $user;
    }
}
