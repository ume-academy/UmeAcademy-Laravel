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

    // Tìm được cả khóa học nháp ...
    public function find(int $id) {
        return Course::findOrFail($id);
    }

    public function getByTeacher(int $id, int $perPage) {
        return Course::where('teacher_id', $id)->paginate($perPage);
    }

    // Chỉ tìm được khóa học đã xuất bản
    public function getById(int $id) {
        return Course::where('status', 2)->findOrFail($id);
    }

    // Lấy danh sách bài học đã hoàn thành của user trong một khóa học
    public function completedLessons(int $courseId, int $userId)
    {
        $course = $this->getById($courseId);
        return $course->lessons()
            ->whereHas('lessonCompleted', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->get();
    }
}
