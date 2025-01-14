<?php

namespace App\Repositories;

use App\Models\RefreshToken;
use App\Repositories\Interfaces\RefreshTokenRepositoryInterface;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    public function create(array $data)
    {
        return RefreshToken::create($data);
    }

    public function updateOrCreate(array $data, array $newData)
    {
        return RefreshToken::updateOrCreate($data, $newData);
    }

    public function findByToken(string $token)
    {
        return RefreshToken::where('token', $token)->first();
    }

    public function delete(string $token)
    {
        return RefreshToken::where('token', $token)->delete();
    }

}
