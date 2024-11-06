<?php

namespace App\Http\Controllers\Api\V1\Teacher;

use App\Contracts\TeacherRegistrationInterface;
use App\Http\Controllers\Controller;

class TeacherRegistrationController extends Controller
{
    public function __construct(
        private TeacherRegistrationInterface $teacherRegistration
    ){}

    public function registerTeacher() {
        try {
            $this->teacherRegistration->registerTeacher();
            return response()->json(['message' => 'Đăng ký trở thành giảng viên thành công.'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
