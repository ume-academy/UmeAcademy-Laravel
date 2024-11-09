<?php

namespace App\Repositories\Interfaces;

use App\Models\Lesson;

interface LessonRepositoryInterface
{
    public function create(array $data);
    public function find(int $id);
    public function syncLessonCompleted(Lesson $lesson, array $userIds);
}
