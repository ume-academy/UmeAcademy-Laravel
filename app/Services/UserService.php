<?php

namespace App\Services;

use App\Traits\HandleFileTrait;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Access\AuthorizationException;
use App\Repositories\Interfaces\UserRepositoryInterface;

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

    public function changePassword(int $userId, string $oldPassword, string $newPassword)
    {
        $user = auth()->user();

        // Kiểm tra mật khẩu cũ
        if (!Hash::check($oldPassword, $user->password)) {
            throw new \Exception('Mật khẩu cũ không chính xác.');
        }

        // Cập nhật mật khẩu mới
        return $this->userRepo->updatePassword($userId, $newPassword);
    }
}
