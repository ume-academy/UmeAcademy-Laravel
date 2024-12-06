<?php

namespace App\Repositories\Interfaces;

interface VoucherRepositoryInterface
{
    public function create(array $data);
    public function getAllByCourse(int $id);
    public function getByCode($code);
    public function find(int $id);
    public function getAllVoucher($perPage);
}
