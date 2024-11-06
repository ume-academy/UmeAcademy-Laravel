<?php

namespace App\Repositories\Interfaces;

interface VoucherRepositoryInterface
{
    public function create(array $data);
    public function getAllByCourseId(int $id);
}
