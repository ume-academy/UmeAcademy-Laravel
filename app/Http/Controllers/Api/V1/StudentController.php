<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\Wallet\WalletResource;
use App\Services\StudentService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct(
        private StudentService $studentService
    ){}

    public function getWalletBalance() {
        try {
            $balance = $this->studentService->getWalletBalance();
            return response()->json(['data' => $balance]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getWalletTransaction(Request $req) {
        try {
            $perPage = $req->input('per_page', 10);

            $transactions = $this->studentService->getWalletTransaction($perPage);
            return WalletResource::collection($transactions);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getWalletTransactionByStudent(Request $req, $id) {
        try {
            $perPage = $req->input('per_page', 10);

            $transactions = $this->studentService->getWalletTransactionByStudent($id, $perPage);
            return WalletResource::collection($transactions);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getPurchasedCoursesByStudent(Request $req, $id) {
        try {
            $perPage = $req->input('per_page', 8);

            $courses = $this->studentService->getPurchasedCoursesByStudent($id, $perPage);
            return CourseResource::collection($courses);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
