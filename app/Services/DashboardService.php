<?php

namespace App\Services;

use App\Models\Course;
use App\Models\TeacherWallet;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getTopTeacher() {
        $topTeachers = TeacherWallet::with('teacher')
            ->withSum(['teacherWalletTransactions as total_earnings' => function ($query) {
                $query->where('type', '!=', 'return_money');
            }], 'balance_tracking')
            ->orderBy('total_earnings', 'desc')
            ->limit(5)
            ->get();
    
        return $topTeachers;
    }

    public function getTopCourses() {
        $courses = Course::all();

        $topCourses = $courses->sortByDesc(function ($course) {
            return $course->total_student;
        })->take(5);;

        return $topCourses;
    }

    public function getStatistics() {
        $revenue = intval(Transaction::where('status', 'success')->sum('discount_price'));
        $profit = intval(Transaction::where('status', 'success')->sum(DB::raw('discount_price - revenue_teacher')));
        $totalTransaction = Transaction::count();
        $totalUser = User::whereNotNull('email_verified_at')->count();
        $totalTeacher = User::whereNotNull('email_verified_at')->whereHas('teacher')->count();
        $totalCourse = Course::where('status', 2)->count();

        $data = [
            'revenue' => $revenue,
            'profit' => $profit,
            'total_transaction' => $totalTransaction,
            'total_user' => $totalUser,
            'total_teacher' => $totalTeacher,
            'total_course' => $totalCourse,
        ];

        return $data;
    }

    public function getRevenue($year) 
    {
        $months = range(1, 12); 
        $data = collect($months)->map(function ($month) use ($year) {

            // Lấy số lượng khóa học đã bán trong tháng
            $coursesSold = Transaction::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->where('status', 'success')
                ->distinct('course_id')
                ->count();

            $revenue = Transaction::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->where('status', 'success')
                ->sum('discount_price');

            // Lấy số lượng user mới đăng ký trong tháng
            $newStudents = User::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereNotNull('email_verified_at')
                ->count();

            // Tính tỷ lệ hoàn tiền: refund_count / total_transaction_count
            $refundCount = Transaction::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->where('status', 'refunded')
                ->count();

            $totalTransactions = Transaction::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();

            $refundRate = $totalTransactions > 0 
                ? round(($refundCount / $totalTransactions) * 100, 2) 
                : 0;

            return [
                'month' => $month,
                'courses_sold' => $coursesSold,
                'revenue' => $revenue,
                'new_students' => $newStudents,
                'total_transaction' => $totalTransactions,
                'refund_rate' => $refundRate,
            ];
        });

        return$data;
    }
}
