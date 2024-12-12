<?php

namespace App\Repositories;

use App\Models\StudentWalletTransaction;
use App\Repositories\Interfaces\StudentWalletTransactionRepositoryInterface;

class StudentWalletTransactionRepository implements StudentWalletTransactionRepositoryInterface
{
    public function getByWalletId(int $id, $perPage) {
        return StudentWalletTransaction::where('student_wallet_id', $id)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);
    }
}
