<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Tymon\JWTAuth\Facades\JWTAuth;

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

    public function getListUser($perPage) {
        $user = JWTAuth::parseToken()->authenticate();
        
        if(!$user || !$user->hasRole('admin')) {
            throw new AuthorizationException('Unauthorized');
        }

        return $this->userRepository->getAllUser($perPage);
    }
}
