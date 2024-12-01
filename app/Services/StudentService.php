<?php

namespace App\Services;

use App\Repositories\Interfaces\StudentWalletRepositoryInterface;
use App\Repositories\Interfaces\StudentWalletTransactionRepositoryInterface;
use Tymon\JWTAuth\Facades\JWTAuth;

class StudentService
{
    public function __construct(
        private StudentWalletRepositoryInterface $studentWalletRepo,
        private StudentWalletTransactionRepositoryInterface $studentWalletTransactionRepo,
    ){}

    public function getWalletBalance() {
        $user = JWTAuth::parseToken()->authenticate();

        $wallet =  $this->studentWalletRepo->getByStudentId($user->id);

        if(!$wallet) {
            throw new \Exception('Tài khoản chưa có ví.');
        }

        return $wallet->balance;
    }

    public function getWalletTransaction($perPage) {
        $user = JWTAuth::parseToken()->authenticate();
        $wallet =  $this->studentWalletRepo->getByStudentId($user->id);
        
        if(!$wallet) {
            throw new \Exception('Tài khoản chưa có ví.');
        }

        return $this->studentWalletTransactionRepo->getByWalletId($wallet->id, $perPage);
    }
}
