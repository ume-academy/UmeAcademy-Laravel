<?php

namespace App\Repositories;

use App\Models\Voucher;
use App\Repositories\Interfaces\VoucherRepositoryInterface;

class VoucherRepository implements VoucherRepositoryInterface
{
    public function create(array $data) {
        return Voucher::create($data);
    }

    public function getAllByCourse(int $id) {
        return Voucher::where('course_id', $id)->get();
    }
}
