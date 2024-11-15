<?php

namespace App\Exceptions\Voucher;

use Exception;

class VoucherNotForCourseException extends Exception
{
    protected $message = 'Voucher không áp dụng cho khóa học này.';
    protected $code = 400;
}
