<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    )
    {}

    public function me(int $userId) 
    {
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            throw new \Exception('User not found.');
        }

        return $user;
    }
}
