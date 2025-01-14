<?php

namespace App\Exceptions\Auth;

use Exception;

class UserNotFoundException extends Exception
{
    public function __construct($message = 'Không tìm thấy người dùng.', $code = 404)
    {
        parent::__construct($message, $code);
    }
}
