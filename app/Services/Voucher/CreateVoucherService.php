<?php

namespace App\Services\Voucher;

use App\Contracts\CreateVoucherServiceInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\VoucherRepositoryInterface;
use App\Traits\ValidationTrait;

class CreateVoucherService implements CreateVoucherServiceInterface
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
}
