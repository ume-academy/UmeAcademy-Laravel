<?php

namespace App\Services;

use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\VoucherRepositoryInterface;
use App\Traits\ValidationTrait;
use App\Traits\VoucherTrait;

class VoucherService
{
    use ValidationTrait, VoucherTrait;

    public function __construct(
        private VoucherRepositoryInterface $voucherRepo,
        private CourseRepositoryInterface $courseRepo
    ) {}

    public function createVoucher(array $data) {

        $teacher = $this->validateTeacher();

        // Kiểm tra quyền sở hữu của khóa học
        $this->validateCourse($teacher, $data['course_id']);

        $data['creator_type'] = 'teacher';
        $data['teacher_id'] = $teacher->id;

        return $this->voucherRepo->create($data);
    }

    public function getVouchersOfCourse(int $courseId) {
        $teacher = $this->validateTeacher();

        // Kiểm tra quyền sở hữu của khóa học
        $this->validateCourse($teacher, $courseId);

        return $this->voucherRepo->getAllByCourse($courseId);
    }

    public function checkVoucher($data) {
        $voucher = $this->voucherRepo->getByCode($data['code']);
        $course = $this->courseRepo->getById($data['course_id']);

        return $this->check($voucher, $course);
    }
}