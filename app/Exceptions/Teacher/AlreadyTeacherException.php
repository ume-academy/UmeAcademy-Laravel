<?php

namespace App\Exceptions\Teacher;

use Exception;

class AlreadyTeacherException extends Exception
{
    public function __construct($message = 'Bạn đã là giảng viên.')
    {
        parent::__construct($message);
    }
}
