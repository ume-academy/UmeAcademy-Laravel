<?php

namespace App\Repositories\Interfaces;

interface TransactionRepositoryInterface
{
    public function create(array $data);
    public function getByCode($code);
    public function updateStatus(int $id, string $status);
}