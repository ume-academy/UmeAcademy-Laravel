<?php

namespace App\Services;

use App\Traits\HandleFileTrait;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Access\AuthorizationException;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

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
        return $this->userRepo->lock($id);
    }

    public function unlock($id) {
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

    public function getUser($id) {
        return $this->userRepo->findById($id);
    }

    public function getListUserSystem($perPage) {
        // Lấy tất cả vai trò
        $roles = Role::pluck('name')->toArray();
    
        // Lấy danh sách người dùng có bất kỳ vai trò nào trong danh sách
        return $this->userRepo->getUserRoles($roles, $perPage);
    }

    public function assignRole($id, $role) {
        $user = $this->userRepo->findById($id);

        if(!$this->userRepo->isSystemUser($user->id)) {
            throw new \Exception('User không phải là user hệ thống');
        }

        $user->syncRoles([$role]);

        return $user;
    }

    public function createUserSystem($data) {
        DB::beginTransaction();
        $data['email_verified_at'] = now();
    
        try {
            $user = $this->userRepo->create($data);
    
            if ($user) {
                $user->syncRoles([$data['role']]);
            }
    
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollback();
    
            throw $e;
        }
    }
}
