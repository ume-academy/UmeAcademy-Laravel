<?php

namespace App\Repositories\Interfaces;

interface TeacherWalletTransactionRepositoryInterface
{
    public function create(array $data);
    public function getByWalletId(int $id, $perPage);
    public function filterRevenue(int $id, $startDate, $endDate);
}
