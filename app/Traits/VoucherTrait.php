<?php

namespace App\Traits;

use App\Exceptions\Voucher\VoucherExpiredException;
use App\Exceptions\Voucher\VoucherNotForCourseException;
use App\Exceptions\Voucher\VoucherNotFoundException;
use App\Exceptions\Voucher\VoucherNotStartedException;
use App\Exceptions\Voucher\VoucherOutOfStockException;

trait VoucherTrait 
{
    public function check($voucher, $course)
    {
        // Kiểm tra xem voucher có tồn tại không
        if (!$voucher) {
            throw new VoucherNotFoundException();
        }

        // Kiểm tra xem voucher có hết hạn không
        $currentDate = now()->format('Y-m-d');
        if ($voucher->start_date > $currentDate) {
            throw new VoucherNotStartedException();
        }

        if ($voucher->end_date < $currentDate) {
            throw new VoucherExpiredException();
        }

        // Kiểm tra số lượng voucher còn lại
        if ($voucher->quantity <= $voucher->used_count) {
            throw new VoucherOutOfStockException();
        }

        // Kiểm tra xem voucher có hợp lệ không
        if ($voucher->course_id && $voucher->course_id != $course->id) {
            throw new VoucherNotForCourseException();
        }

        return $voucher;
    }
}
