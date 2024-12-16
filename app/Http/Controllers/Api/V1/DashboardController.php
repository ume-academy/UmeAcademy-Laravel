<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\Statistic\TopTeacherResource;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService
    ){}

    public function getTopTeacher()
    {
        $topTeachers = $this->dashboardService->getTopTeacher();

        return TopTeacherResource::collection($topTeachers);
    }

    public function getTopCourses() {
        $topCourses = $this->dashboardService->getTopCourses();

        return CourseResource::collection($topCourses);
    }
}
