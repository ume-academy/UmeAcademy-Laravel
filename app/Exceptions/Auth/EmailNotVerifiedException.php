<?php

namespace App\Exceptions\Auth;

use Exception;

class EmailNotVerifiedException extends Exception
{
    public function __construct($message = 'Email chưa được xác minh.')
    {
        parent::__construct($message);
    }
}
