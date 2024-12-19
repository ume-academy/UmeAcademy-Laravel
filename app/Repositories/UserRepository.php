<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Str;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Spatie\Permission\Models\Role;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data)
    {
        return User::create($data);
    }

    public function findById(int $id)
    {
        return User::find($id);
    }
    public function update(int $id,array $data){
        $user = User::findOrFail($id);
        
        $user->update($data);
        return $user;
    }

    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function findByRefreshToken($refreshToken)
    {
        return User::where('refresh_token', $refreshToken)->first();
    }

    public function createRefreshToken(User $user)
    {
        $refreshToken = Str::random(60);
        $user->refresh_token = $refreshToken;
        $user->save();

        return $refreshToken;
    }

    public function removeRefreshToken(User $user)
    {
        $user->refresh_token = null;
        $user->save();
    }

    public function updateRefreshToken(User $user, $refreshToken)
    {
        $user->refresh_token = $refreshToken;
        $user->save();
    }

    public function getAllUser($perPage, $status = null) {
        $query = User::query();
    
        // Lọc theo trạng thái nếu có
        if ($status !== null) {
            if ($status == 'active') {
                $query->where('is_lock', false);
            } elseif ($status == 'locked') {
                $query->where('is_lock', true);
            }
        }
    
        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
    
    public function getAllTeacher($perPage, $status = null) {
        $query = User::whereHas('teacher');
    
        if ($status !== null) {
            if ($status == 'active') {
                $query->where('is_lock', false);
            } elseif ($status == 'locked') {
                $query->where('is_lock', true);
            }
        }
    
        // Sắp xếp và phân trang
        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function lock(int $id) {
        $user = $this->findById($id);

        $user->is_lock = true;
        $user->save();
        return $user;
    }

    public function unlock(int $id) {
        $user = $this->findById($id);

        $user->is_lock = false;
        $user->save();
        return $user;
    }

    public function updatePassword(int $userId, string $newPassword): bool
    {
        $user = User::findOrFail($userId);
        $user->password = bcrypt($newPassword);
        return $user->save();
    }

    public function getUserRoles(array $roles, $perPage, $status) {
        $query = User::role($roles);

        if ($status !== null) {
            if ($status == 'active') {
                $query->where('is_lock', false);
            } elseif ($status == 'locked') {
                $query->where('is_lock', true);
            }
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function isSystemUser(int $id) {
        $user = $this->findById($id);
        $systemRoles = Role::pluck('name')->toArray(); 
        
        return $user->hasAnyRole($systemRoles);
    }
}
