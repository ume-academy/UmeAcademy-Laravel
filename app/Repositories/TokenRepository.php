<?php

namespace App\Repositories;

use App\Models\RefreshToken;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\TokenRepositoryInterface;

class TokenRepository implements TokenRepositoryInterface
{
    public function storeRefreshToken(int $userId, string $refreshToken) 
    {
        DB::table('refresh_tokens')->updateOrInsert(
            ['user_id' => $userId],
            ['refresh_token' => $refreshToken, 'updated_at' => now()]
        );
    }

    public function getRefreshToken(int $userId) 
    {
        return RefreshToken::where('user_id', $userId)->get('refresh_token');
    }

    public function deleteRefreshToken(string $refreshToken) 
    {
        return RefreshToken::where('refresh_token', $refreshToken)->delete();
    }

    public function updateOrCreate(array $data, array $newToken)
    {
        return RefreshToken::updateOrCreate($data, $newToken);
    }

    public function findByToken(string $token)
    {
        return RefreshToken::where('refresh_token', $token)->first();
    }
}
