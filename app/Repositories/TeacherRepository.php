<?php

namespace App\Repositories;

use App\Models\Teacher;
use App\Repositories\Interfaces\TeacherRepositoryInterface;

class TeacherRepository implements TeacherRepositoryInterface
{
    public function create(array $data) {
        return Teacher::create($data);
    }
}
