<?php

namespace App\Services;

use App\Models\Course;
use App\Models\TeacherWallet;

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
}
