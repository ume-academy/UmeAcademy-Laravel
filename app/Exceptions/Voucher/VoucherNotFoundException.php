<?php

namespace App\Exceptions\Voucher;

use Exception;

class VoucherNotFoundException extends Exception
{
    protected $message = 'Voucher không tồn tại.';
    protected $code = 404;
}
