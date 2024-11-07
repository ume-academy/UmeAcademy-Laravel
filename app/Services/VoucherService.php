<?php

namespace App\Services;

use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\VoucherRepositoryInterface;
use App\Traits\ValidationTrait;

class VoucherService
{
    use ValidationTrait;

    public function __construct(
        private VoucherRepositoryInterface $voucherRepo,
        private CourseRepositoryInterface $courseRepo
    ) {}

    public function createVoucher(array $data) {

        $user = $this->validateTeacher();

        // Kiểm tra quyền sở hữu của khóa học
        $this->validateCourse($user, $data['course_id']);

        return $this->voucherRepo->create($data);
    }

    public function getAllVoucher(int $courseId) {
        $user = $this->validateTeacher();

        // Kiểm tra quyền sở hữu của khóa học
        $this->validateCourse($user, $courseId);

        return $this->voucherRepo->getAllByCourse($courseId);
    }
}
