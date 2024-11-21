<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Teacher\TeacherResource;
use App\Services\TeacherService;

class TeacherController extends Controller
{
    public function __construct(
        private TeacherService $teacherService
    ){}

    public function registerTeacher() {
        try {
            $this->teacherService->registerTeacher();
            return response()->json(['message' => 'Đăng ký trở thành giảng viên thành công.'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function checkTeacher() {
        try {
            $isTeacher = $this->teacherService->checkTeacher();
            return response()->json(['is_teacher' => $isTeacher], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getInfoTeacher($id) {
        try {
            $teacher = $this->teacherService->getInfoTeacher($id);
            return new TeacherResource($teacher);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
