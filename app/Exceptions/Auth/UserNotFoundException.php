<?php

namespace App\Exceptions\Auth;

use Exception;

class UserNotFoundException extends Exception
{
    public function __construct($message = 'Không tìm tháy người dùng.')
    {
        parent::__construct($message);
    }
}
