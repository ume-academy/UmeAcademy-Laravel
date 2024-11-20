<?php

namespace App\Repositories\Interfaces;

interface VoucherUsageRepositoryInterface
{
    public function create(array $data);
    public function getByTransaction(int $id);
    public function updateStatus(int $id, $status);
    public function delete(int $id);
}
