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

    public function getAllUser($perPage) {
        return User::orderBy('created_at', 'desc')->paginate($perPage);
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

    public function getUserRoles(array $roles, $perPage) {
        return User::role($roles)->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function isSystemUser(int $id) {
        $user = $this->findById($id);
        $systemRoles = Role::pluck('name')->toArray(); 
        
        return $user->hasAnyRole($systemRoles);
    }
}
