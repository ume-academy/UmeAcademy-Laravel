<?php

namespace App\Exceptions\Auth;

use Exception;

class TooManyVerificationRequestsException extends Exception
{
    public function __construct($message = 'Bạn đã yêu cầu quá nhiều hãy thử lại sau ít phút.')
    {
        parent::__construct($message);
    }
}
