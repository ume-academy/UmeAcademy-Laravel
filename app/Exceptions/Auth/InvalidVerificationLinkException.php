<?php

namespace App\Exceptions\Auth;

use Exception;

class InvalidVerificationLinkException extends Exception
{
    public function __construct($message = 'Link xác minh không hợp lệ.')
    {
        parent::__construct($message);
    }
}
