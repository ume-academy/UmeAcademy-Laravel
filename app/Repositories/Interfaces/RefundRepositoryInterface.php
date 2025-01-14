<?php

namespace App\Repositories\Interfaces;

interface RefundRepositoryInterface
{
    public function getAll($perPage, $status);
    public function find(int $id);
    public function updateStatus(int $id, $status);
}
