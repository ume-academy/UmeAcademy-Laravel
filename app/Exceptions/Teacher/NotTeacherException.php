<?php

namespace App\Exceptions\Teacher;

use Exception;

class NotTeacherException extends Exception
{
    public function __construct($message = 'Bạn chưa đăng ký trở thành giảng viên.')
    {
        parent::__construct($message);
    }
}
