<?php

namespace App\Repositories;

use App\Models\Wallet;
use App\Repositories\Interfaces\UserWalletRepositoryInterface;

class UserWalletRepository implements UserWalletRepositoryInterface
{
    public function createWallet(int $user_id)
    {
        return Wallet::create([
            'user_id' => $user_id,
            'balance' => 0, // Mặc định số dư là 0
        ]);
    }
}
