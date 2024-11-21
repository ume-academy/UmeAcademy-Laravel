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

    // Đồng bộ dữ liệu vào bảng lesson_completeds
    public function syncLessonCompleted(Lesson $lesson, array $userIds)
    {
        return $lesson->lessonCompleted()->attach($userIds);
    }

    public function update(int $id, array $data) {
        $lesson = $this->find($id);

        return $lesson->update($data);
    }
}
