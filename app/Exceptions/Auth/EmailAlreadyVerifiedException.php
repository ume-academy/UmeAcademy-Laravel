<?php

namespace App\Exceptions\Auth;

use Exception;

class EmailAlreadyVerifiedException extends Exception
{
    public function __construct($message = 'Email đã được xác minh.', $code = 409)
    {
        parent::__construct($message, $code);
    }
}
