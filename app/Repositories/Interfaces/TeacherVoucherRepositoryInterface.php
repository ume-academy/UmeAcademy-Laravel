<?php

namespace App\Repositories\Interfaces;

interface TeacherVoucherRepositoryInterface
{
    public function create(array $data);
    public function getAllByCourse(int $id);
}
