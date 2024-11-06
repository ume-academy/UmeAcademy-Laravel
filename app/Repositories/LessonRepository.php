<?php

namespace App\Repositories;

use App\Models\Lesson;
use App\Repositories\Interfaces\LessonRepositoryInterface;

class LessonRepository implements LessonRepositoryInterface
{
    public function create(array $data) {
        return Lesson::create($data);
    }

    public function find(int $id) {
        return Lesson::findOrFail($id);
    }
}
