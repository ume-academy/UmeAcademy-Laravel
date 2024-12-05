<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Revenue\RevenueRequest;
use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\Teacher\TeacherResource;
use App\Http\Resources\Wallet\WalletResource;
use App\Services\TeacherService;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function __construct(
        private TeacherService $teacherService
    ){}

    public function registerTeacher() {
        try {
            $this->teacherService->registerTeacher();
            return response()->json([
                'status' => true,
                'message' => 'Đăng ký trở thành giảng viên thành công.'
            ], 200);

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

    public function getWalletBalance() {
        try {
            $balance = $this->teacherService->getWalletBalance();
            return response()->json(['data' => $balance]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getWalletTransaction(Request $req) {
        try {
            $perPage = $req->input('per_page', 10);

            $transactions = $this->teacherService->getWalletTransaction($perPage);
            return WalletResource::collection($transactions);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getStatistic() {
        try {
            $statistic = $this->teacherService->getStatistic();

            return response()->json(['data' => $statistic]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getRevenue(RevenueRequest $req) {
        try {
            $data = $req->only(['start_date', 'end_date']);

            $revenue = $this->teacherService->getRevenue($data);

            return response()->json(['data' => $revenue]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getStatisticOfTeacher($id) {
        try {
            $statistic = $this->teacherService->getStatisticOfTeacher($id);

            return response()->json(['data' => $statistic]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getWalletTransactionByTeacher(Request $req, $id) {
        try {
            $perPage = $req->input('per_page', 10);

            $transactions = $this->teacherService->getWalletTransactionByTeacher($id, $perPage);
            return WalletResource::collection($transactions);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCoursesByTeacher(Request $req, $id) {
        try {
            $perPage = $req->input('per_page', 10);

            $courses = $this->teacherService->getCoursesByTeacher($id, $perPage);

            return CourseResource::collection($courses);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getProfile() {
        try {
            $teacher = $this->teacherService->getProfile();

            return new TeacherResource($teacher);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
