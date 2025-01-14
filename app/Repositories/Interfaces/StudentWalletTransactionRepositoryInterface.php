<?php

namespace App\Repositories\Interfaces;

interface StudentWalletTransactionRepositoryInterface
{
    public function getByWalletId(int $id, $perPage);
}
