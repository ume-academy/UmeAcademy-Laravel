<?php

namespace App\Repositories;

use App\Models\Course;
use App\Repositories\Interfaces\CourseRepositoryInterface;

class CourseRepository implements CourseRepositoryInterface
{
    public function create(array $data)
    {
        return Course::create($data);
    }

    public function find(int $id) {
        return Course::findOrFail($id);
    }

    public function getByTeacher(int $id, int $perPage) {
        return Course::where('teacher_id', $id)->paginate($perPage);
    }
}
