<?php

namespace App\Repositories\Interfaces;

interface WithdrawMethodRepositoryInterface
{
    // 
    public function create(array $data);
    public function getWithdrawMethod(int $teacherId);
}