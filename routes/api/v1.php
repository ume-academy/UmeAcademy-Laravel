<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegistrationController;
use App\Http\Controllers\Api\V1\Auth\VerificationController;
use App\Http\Controllers\Api\V1\ChapterController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\LessonController;
use App\Http\Controllers\Api\V1\TeacherController;
use App\Http\Controllers\Api\V1\VoucherController;

Route::prefix('/auth')
->middleware(['api', 'jwt.auth'])
->group(function () {
    Route::post('/register', [RegistrationController::class, 'registerWithEmail'])->withoutMiddleware('jwt.auth');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->name('verification.verify')->withoutMiddleware('jwt.auth');

    Route::post('/email/resend', [VerificationController::class, 'resend'])
    ->name('verification.resend')->withoutMiddleware('jwt.auth');

    Route::post('/login', [LoginController::class, 'loginWithEmail'])->withoutMiddleware('jwt.auth');
    // Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('jwt.auth')->group(function () {
    // Student
    Route::post('/register/teacher', [TeacherController::class, 'registerTeacher']);
    Route::get('/course/{id}/information', [CourseController::class, 'getInfoCourse'])->withoutMiddleware('jwt.auth');
    Route::get('/course/{id}/statistic', [CourseController::class, 'getStatisticCourse'])->withoutMiddleware('jwt.auth');
    Route::get('/course/{id}/content', [CourseController::class, 'getContentCourse'])->withoutMiddleware('jwt.auth');
    Route::get('/course/{id}/overview', [CourseController::class, 'getOverviewCourse'])->withoutMiddleware('jwt.auth');
    Route::get('/course/{id}/teacher-information', [CourseController::class, 'getCourseTeacherInformation'])->withoutMiddleware('jwt.auth');

    // Teacher
    Route::prefix('/teacher')->group(function () {
        Route::get('/courses', [CourseController::class, 'getCoursesOfTeacher']);
        Route::post('/courses', [CourseController::class, 'createCourse']);

        Route::post('/course/{id}/chapters', [ChapterController::class, 'createChapter']);

        Route::post('/course/{id}/chapter/{chapterId}/lessons', [LessonController::class, 'createLesson']);
        
        Route::post('/course/{id}/chapter/{chapterId}/lesson/{lessonId}/videos', [LessonController::class, 'createVideo']);

        Route::post('/course/{id}/vouchers', [VoucherController::class, 'createVoucher']);
        Route::get('/course/{id}/vouchers', [VoucherController::class, 'getAllVoucher']);
    });
});