<?php

namespace App\Repositories\Interfaces;

interface UserWalletRepositoryInterface
{
    public function createWallet(int $user_id);
}
