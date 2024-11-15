<?php

namespace App\Exceptions\Voucher;

use Exception;

class VoucherOutOfStockException extends Exception
{
    protected $message = 'Voucher đã được sử dụng hết.';
    protected $code = 400;
}
