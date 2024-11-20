<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Repositories\Interfaces\TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function create(array $data)
    {
        return Transaction::create($data);
    }

    public function getByCode($code) {
        return Transaction::where('transaction_code', $code)->first();
    }

    public function updateStatus(int $id, string $status) {
        $transaction = Transaction::findOrFail($id);

        $transaction->status = $status;
        $transaction->save();
    }
}