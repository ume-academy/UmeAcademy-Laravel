<?php

namespace App\Repositories;

use App\Models\TeacherVoucher;
use App\Repositories\Interfaces\TeacherVoucherRepositoryInterface;

class TeacherVoucherRepository implements TeacherVoucherRepositoryInterface
{
    public function create(array $data) {
        return TeacherVoucher::create($data);
    }

    public function getAllByCourse(int $id) {
        return TeacherVoucher::where('course_id', $id)->get();
    }
}
