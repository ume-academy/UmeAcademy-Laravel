<?php

namespace App\Repositories;

use App\Models\TeacherWalletTransaction;
use App\Repositories\Interfaces\TeacherWalletTransactionRepositoryInterface;

class TeacherWalletTransactionRepository implements TeacherWalletTransactionRepositoryInterface
{
    public function create(array $data) {
        return TeacherWalletTransaction::create($data);
    }
}
