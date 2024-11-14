<?php

namespace App\Services;

use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\TeacherVoucherRepositoryInterface;
use App\Traits\ValidationTrait;

class TeacherVoucherService
{
    use ValidationTrait;

    public function __construct(
        private TeacherVoucherRepositoryInterface $teacherVoucherRepo,
        private CourseRepositoryInterface $courseRepo
    ) {}

    public function createVoucher(array $data) {

        $user = $this->validateTeacher();

        // Kiểm tra quyền sở hữu của khóa học
        $this->validateCourse($user, $data['course_id']);

        return $this->teacherVoucherRepo->create($data);
    }

    public function getAllVoucher(int $courseId) {
        $user = $this->validateTeacher();

        // Kiểm tra quyền sở hữu của khóa học
        $this->validateCourse($user, $courseId);

        return $this->teacherVoucherRepo->getAllByCourse($courseId);
    }
}
