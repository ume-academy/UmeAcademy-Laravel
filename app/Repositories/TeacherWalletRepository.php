<?php

namespace App\Repositories;

use App\Models\TeacherWallet;
use App\Repositories\Interfaces\TeacherWalletRepositoryInterface;

class TeacherWalletRepository implements TeacherWalletRepositoryInterface
{
    public function create(array $data) {
        return TeacherWallet::create($data);
    }
}
