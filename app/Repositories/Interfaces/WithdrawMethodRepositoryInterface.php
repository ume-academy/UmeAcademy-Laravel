<?php

namespace App\Repositories\Interfaces;

interface WithdrawMethodRepositoryInterface
{
    public function create(array $data);
    public function getWithdrawMethod(int $teacherId);
    public function getAllBank();
    public function update($id, $data);
    public function getAllRequest($startDate, $endDate, $perPage);
    public function updateStatus(int $id, $status);
    public function find(int $id);
}
