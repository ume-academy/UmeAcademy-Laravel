<?php

namespace App\Repositories;

use App\Models\Teacher;
use App\Repositories\Interfaces\TeacherRepositoryInterface;

class TeacherRepository implements TeacherRepositoryInterface
{
    public function create(array $data) {
        return Teacher::create($data);
    }

    public function getById($id) {
        return Teacher::findOrFail($id);
    }
    
    public function update(int $id, array $data) {
        $teacher = $this->getById($id);
        $teacher->update($data);

        return $teacher;
    }
}
