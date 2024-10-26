<?php

namespace App\Exceptions\Auth;

use Exception;

class EmailAlreadyVerifiedException extends Exception
{
    public function __construct($message = 'Email đã được xác minh.')
    {
        parent::__construct($message);
    }
}
