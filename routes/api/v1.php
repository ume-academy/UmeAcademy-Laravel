<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ChapterController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\LessonController;
use App\Http\Controllers\Api\V1\ReviewController;
use App\Http\Controllers\Api\V1\TeacherController;
use App\Http\Controllers\Api\V1\VoucherController;
use App\Http\Controllers\Api\V1\EmailVerificationController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\Teacher\TeacherRegistrationController;

Route::prefix('/auth')
    ->group(function () {

        Route::post('/register/{type}', [AuthController::class, 'register']);

        Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
            ->name('verification.verify');

        Route::post('/email/resendVerificationEmail', [EmailVerificationController::class, 'resendVerificationEmail'])
            ->name('verification.resend');

        Route::post('/login/{type}', [AuthController::class, 'login']);

        // Các route cần xác thực JWT
        Route::middleware('verify.jwt.token')
            ->group(function () {
                Route::post('/logout', [AuthController::class, 'logout']);
                Route::post('/refreshToken', [AuthController::class, 'refreshTokens']);
            }
        );
    }
);

Route::middleware('jwt.auth')->group(function () {
    // Student
    Route::post('/teachers/register', [TeacherController::class, 'registerTeacher']);
    Route::post('/teachers/check', [TeacherController::class, 'checkTeacher']);
    
    Route::get('/course/{id}/information', [CourseController::class, 'getInfoCourse'])->withoutMiddleware('jwt.auth');
    Route::get('/course/{id}/statistic', [CourseController::class, 'getStatisticCourse'])->withoutMiddleware('jwt.auth');
    Route::get('/course/{id}/content', [CourseController::class, 'getContentCourse'])->withoutMiddleware('jwt.auth');
    Route::get('/course/{id}/overview', [CourseController::class, 'getOverviewCourse'])->withoutMiddleware('jwt.auth');
    Route::get('/course/{id}/teacher-information', [CourseController::class, 'getCourseTeacherInformation'])->withoutMiddleware('jwt.auth');
    Route::get('/course/{id}/reviews', [ReviewController::class, 'getReviewCourse'])->withoutMiddleware('jwt.auth');

    Route::post('/vouchers/check', [VoucherController::class, 'checkVoucher'])->withoutMiddleware('jwt.auth');

    // Learning
    Route::prefix('/learning')->group(function () {
        Route::get('/course/{id}/content', [CourseController::class, 'getPurchasedCourseContent']);

        Route::post('/course/{id}/chapter/{chapterId}/lesson/{lessonId}/complete', [LessonController::class, 'markLessonCompleted']);
    });

    // Teacher
    Route::prefix('/teacher')->group(function () {
        Route::get('/courses', [CourseController::class, 'getCoursesOfTeacher']);
        Route::post('/courses', [CourseController::class, 'createCourse']);

        Route::post('/course/{id}/chapters', [ChapterController::class, 'createChapter']);

        Route::post('/course/{id}/chapter/{chapterId}/lessons', [LessonController::class, 'createLesson']);
        
        Route::post('/course/{id}/chapter/{chapterId}/lesson/{lessonId}/videos', [LessonController::class, 'createVideo']);

        Route::post('/course/{id}/vouchers', [VoucherController::class, 'createVoucher']);
        Route::get('/course/{id}/vouchers', [VoucherController::class, 'getVouchersOfCourse']);
    });

    // Category
    Route::get('/categories', [CategoryController::class, 'getAllCategories'])->withoutMiddleware('jwt.auth');
    Route::post('/categories', [CategoryController::class, 'storeCategories']);
});

