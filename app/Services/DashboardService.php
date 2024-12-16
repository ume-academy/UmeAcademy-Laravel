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
        $topTeachers = TeacherWallet::with('teacher')->orderBy('total_earnings', 'desc')->limit(5)->get();

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
        $revenue = intval(Transaction::sum('discount_price'));
        $profit = intval(Transaction::sum(DB::raw('discount_price - revenue_teacher')));
        $totalTransaction = Transaction::count();
        $totalUser = User::count();
        $totalTeacher = User::whereHas('teacher')->count();
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
}
