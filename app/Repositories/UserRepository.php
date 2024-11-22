<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Str;
use App\Repositories\Interfaces\UserRepositoryInterface;

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
        return User::paginate($perPage);
    }
}
