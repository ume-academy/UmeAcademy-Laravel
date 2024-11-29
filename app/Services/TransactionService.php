<?php

namespace App\Services;

use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class TransactionService
{
    public function __construct(
        private TransactionRepositoryInterface $transactionRepo
    ){}

    public function getTransactionHistory($perPage) {
        $user = JWTAuth::parseToken()->authenticate();

        return $this->transactionRepo->getByUserId($user->id, $perPage);
    }

    public function getAllTransaction($perPage) {
        $user = JWTAuth::parseToken()->authenticate();

        if(!$user || !$user->hasRole('admin')) {
            throw new AuthorizationException('Unauthorized');
        }

        return $this->transactionRepo->getAll($perPage);
    }
}
