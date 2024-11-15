<?php

namespace App\Exceptions\Voucher;

use Exception;

class VoucherExpiredException extends Exception
{
    protected $message = 'Voucher đã hết hạn.';
    protected $code = 400;
}
