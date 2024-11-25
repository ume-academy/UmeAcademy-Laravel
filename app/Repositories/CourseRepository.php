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

    public function getCourseOfTeacher(int $id) {
        return Course::where('teacher_id', $id)->where('status', 2)->get();
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

    // Đồng bộ dữ liệu vào bảng course_enrolled
    public function syncCourseEnrolled(Course $course, array $userIds)
    {
        return $course->courseEnrolled()->attach($userIds);
    }

    // Lấy khóa học đã mua của học sinh
    public function getCourseOfStudent($user, $perPage) {
        return $user->enrolledCourses()->paginate($perPage);
    }

    public function update(int $id, array $data) {
        $course = $this->find($id);

        return $course->update($data);
    }
    
    public function getByIds(array $ids) {
        return Course::whereIn('id', $ids)->where('status', 2)->get();
    }
    
    public function updateStatus(int $id, $status) {
        $course = $this->find($id);

        $course->status = $status;
        $course->save();
    }
}
