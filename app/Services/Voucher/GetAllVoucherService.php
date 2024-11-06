<?php

namespace App\Services\Voucher;

use App\Contracts\GetAllVoucherInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\VoucherRepositoryInterface;
use App\Traits\ValidationTrait;

class GetAllVoucherService implements GetAllVoucherInterface
{
    use ValidationTrait;

    public function __construct(
        private VoucherRepositoryInterface $voucherRepo,
        private CourseRepositoryInterface $courseRepo
    ) {}

    public function getAllVoucher(int $courseId) {
        $user = $this->validateTeacher();

        // Kiểm tra quyền sở hữu của khóa học
        $this->validateCourse($user, $courseId);

        return $this->voucherRepo->getAllByCourse($courseId);
    }
}
