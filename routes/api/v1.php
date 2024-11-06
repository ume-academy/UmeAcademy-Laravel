<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegistrationController;
use App\Http\Controllers\Api\V1\Auth\VerificationController;
use App\Http\Controllers\Api\V1\Chapter\ChapterController;
use App\Http\Controllers\Api\V1\Course\CourseController;
use App\Http\Controllers\Api\V1\Lesson\LessonController;
use App\Http\Controllers\Api\V1\Teacher\TeacherRegistrationController;
use App\Http\Controllers\Api\V1\Voucher\VoucherController;

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
    Route::post('/register/teacher', [TeacherRegistrationController::class, 'registerTeacher']);

    // Create course
    Route::prefix('/teacher')->group(function () {
        Route::post('/courses', [CourseController::class, 'createCourse']);

        Route::post('/course/{id}/chapters', [ChapterController::class, 'createChapter']);

        Route::post('/course/{id}/chapter/{chapterId}/lessons', [LessonController::class, 'createLesson']);
        
        Route::post('/course/{id}/chapter/{chapterId}/lesson/{lessonId}/videos', [LessonController::class, 'createVideo']);

        Route::post('/course/{id}/vouchers', [VoucherController::class, 'createVoucher']);
    });
});