<?php

namespace App\Repositories;

use App\Models\StudentWallet;
use App\Repositories\Interfaces\StudentWalletRepositoryInterface;

class StudentWalletRepository implements StudentWalletRepositoryInterface
{
    public function getByStudentId(int $id) {
        return StudentWallet::where('user_id', $id)->first();
    }
}
