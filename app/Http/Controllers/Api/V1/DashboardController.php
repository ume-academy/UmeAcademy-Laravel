<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\Statistic\TopTeacherResource;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService
    ){}

    public function getTopTeacher()
    {
        try {
            $topTeachers = $this->dashboardService->getTopTeacher();

            return TopTeacherResource::collection($topTeachers);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getTopCourses() {
        try {
            $topCourses = $this->dashboardService->getTopCourses();

            return CourseResource::collection($topCourses);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getStatistics() {
        try {
            $statistic = $this->dashboardService->getStatistics();

            return response()->json(['data' => $statistic]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getRevenue(Request $req) {
        try {
            $year = $req->input('year', now()->year);
            $revenues = $this->dashboardService->getRevenue($year);

            return response()->json(['data' => $revenues]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
