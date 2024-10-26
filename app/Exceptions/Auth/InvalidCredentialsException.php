<?php

namespace App\Exceptions;

use Exception;

class InvalidCredentialsException extends Exception
{
    public function __construct($message = 'Tài khoản hoặc mật khẩu không chính xác.')
    {
        parent::__construct($message);
    }
}
