<?php

namespace App\Exceptions\Voucher;

use Exception;

class VoucherNotStartedException extends Exception
{
    protected $message = 'Voucher chưa tới ngày sử dụng.';
    protected $code = 400;
}
