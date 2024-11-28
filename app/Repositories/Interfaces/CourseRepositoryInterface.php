<?php

namespace App\Repositories\Interfaces;

use App\Models\Course;

interface CourseRepositoryInterface
{
    public function getByTeacher(int $id, int $perPage);
    public function create(array $data);
    public function find(int $id);
    public function getById(int $id);
    public function completedLessons(int $courseId, int $userId);
    public function syncCourseEnrolled(Course $course, array $userIds);
    public function getCourseOfStudent($user, $perPage);
    public function update(int $id, array $data);
    public function getByIds(array $ids);
    public function updateStatus(int $id, $status);
    public function getCourseOfTeacher(int $id);
    public function getByCategory(int $id, $perPage);
    public function filter($params);
}
