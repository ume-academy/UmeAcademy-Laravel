<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Traits\HandleFileTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    use HandleFileTrait;

    public function __construct(
        private UserRepositoryInterface $userRepo
    ){}

    public function me(int $userId) 
    {
        $user = $this->userRepo->findById($userId);

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

        return $this->userRepo->getAllUser($perPage);
    }
    
    public function updateProfile($data)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (isset($data['avatar'])) {
            $data['avatar'] = $this->handleAvatar($data['avatar']);
        } else {
            $data['avatar'] = $user->avatar;
        }

        // Cập nhật vào db
        return $this->userRepo->update($user->id, $data);
    }

    public function lock($id) {
        $user = JWTAuth::parseToken()->authenticate();
        
        if(!$user || !$user->hasRole('admin')) {
            throw new AuthorizationException('Unauthorized');
        }

        return $this->userRepo->lock($id);
    }

    public function unlock($id) {
        $user = JWTAuth::parseToken()->authenticate();
        
        if(!$user || !$user->hasRole('admin')) {
            throw new AuthorizationException('Unauthorized');
        }

        return $this->userRepo->unlock($id);
    }

    private function handleAvatar($file)
    {
        $fileName = HandleFileTrait::generateName($file);
        HandleFileTrait::uploadFile($file, $fileName, 'users');
        
        return $fileName;
    }
}
