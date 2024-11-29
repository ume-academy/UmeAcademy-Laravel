<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\FeeController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\LessonController;
use App\Http\Controllers\Api\V1\ReviewController;
use App\Http\Controllers\Api\V1\ChapterController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\TeacherController;
use App\Http\Controllers\Api\V1\VoucherController;
use App\Http\Controllers\Api\V1\LevelController;
use App\Http\Controllers\Api\V1\SearchController;
use App\Http\Controllers\Api\V1\Teacher\TeacherRegistrationController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\TransactionController;
use App\Http\Controllers\Api\V1\PaymentMethodController;
use App\Http\Controllers\Api\V1\ForgotPasswordController;
use App\Http\Controllers\Api\V1\WithdrawMethodController;
use App\Http\Controllers\Api\V1\EmailVerificationController;

Route::prefix('/auth')
    ->group(function () {

        Route::post('/register/{type}', [AuthController::class, 'register']);

        Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
            ->name('verification.verify');

        Route::post('/resend-verify-email', [EmailVerificationController::class, 'resend'])
            ->name('verification.resend');

        Route::post('/login/{type}', [AuthController::class, 'login']);

        Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);

        Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])
            ->name('password.reset');

        // Các route cần xác thực JWT
        Route::middleware('verify.jwt.token')
            ->group(function () {
                Route::post('/logout', [AuthController::class, 'logout']);
                Route::post('/refreshToken', [AuthController::class, 'refreshTokens']);
                Route::post('/me', [AuthController::class, 'me']);
            }
        );
    }
);

// Student 
Route::prefix('teachers')
    ->middleware('verify.jwt.token')
    ->group(function () {
        Route::post('/register', [TeacherController::class, 'registerTeacher']);
        Route::post('/check', [TeacherController::class, 'checkTeacher']);
    }
);

Route::middleware('verify.jwt.token')->group(function() {
    Route::get('/purchased-courses', [CourseController::class, 'getPurchasedCourses']);
});


// Admin
Route::prefix('admin')
    ->middleware('verify.jwt.token')
    ->group(function () {
        // Category
        Route::post('/categories', [CategoryController::class, 'storeCategories']);
        Route::get('/categories/{id}', [CategoryController::class, 'getCategory']);
        Route::put('/categories/{id}', [CategoryController::class, 'updateCategory']);
        Route::delete('/categories/{id}', [CategoryController::class, 'deleteCategory']);

        // Fee
        Route::put('/fee/{id}', [FeeController::class, 'update']);
        Route::get('/fee/{id}', [FeeController::class, 'get']);

        // Payment method
        Route::post('/payment-methods', [PaymentMethodController::class, 'createPaymentMethod']);
        Route::put('/payment-methods/{id}', [PaymentMethodController::class, 'updatePaymentMethod']);
        Route::delete('/payment-methods/{id}', [PaymentMethodController::class, 'deletePaymentMethod']);
        Route::get('/payment-methods/{id}', [PaymentMethodController::class, 'detailPaymentMethod']);

        Route::get('/users', [UserController::class, 'getListUser']);

        Route::post('user/{id}/lock', [UserController::class, 'lock']);
        Route::post('user/{id}/unlock', [UserController::class, 'unlock']);

        Route::get('/teacher/{id}/statistic', [TeacherController::class, 'getStatisticOfTeacher']);

        Route::post('/course/{id}/approval', [CourseController::class, 'approval']);

        Route::get('/courses', [CourseController::class, 'getAllCourse']);
    }
);

// Teacher
Route::prefix('/teacher')
    ->middleware('verify.jwt.token')
    ->group(function () {
        Route::get('/courses', [CourseController::class, 'getCoursesOfTeacher']);

        Route::post('/courses', [CourseController::class, 'createCourse']);

        Route::post('/course/{id}/chapters', [ChapterController::class, 'createChapter']);
        Route::put('/course/{id}/chapter/{chapterId}', [ChapterController::class, 'updateChapter']);

        Route::post('/course/{id}/chapter/{chapterId}/lessons', [LessonController::class, 'createLesson']);
        Route::put('/course/{id}/chapter/{chapterId}/lesson/{lessonId}', [LessonController::class, 'updateLesson']);
        
        Route::post('/course/{id}/chapter/{chapterId}/lesson/{lessonId}/videos', [LessonController::class, 'createVideo']);

        Route::post('/course/{id}/chapter/{chapterId}/lesson/{lessonId}/resources', [LessonController::class, 'createResource']);

        Route::post('/course/{id}/vouchers', [VoucherController::class, 'createVoucher']);

        Route::get('/course/{id}/vouchers', [VoucherController::class, 'getVouchersOfCourse']);

        Route::get('/course/{id}', [CourseController::class, 'getCourse']);
        Route::put('/course/{id}', [CourseController::class, 'updateCourse']);

        Route::put('/course/{id}/target-course', [CourseController::class, 'updateTargetCourse']);

        Route::post('/course/{id}/course-approval-request', [CourseController::class, 'requestApprovalCourse']);

        Route::get('/wallet-balance', [TeacherController::class, 'getWalletBalance']);

        // Withdraw
        Route::post('/withdraw-method', [WithdrawMethodController::class, 'addWithdrawMethod']);
        Route::get('/withdraw-method', [WithdrawMethodController::class, 'getWithdrawMethod']);

        Route::get('/course/{id}/students', [CourseController::class, 'getStudentsOfCourse']);

        Route::get('/wallet-transaction', [TeacherController::class, 'getWalletTransaction']);

        Route::get('/statistic', [TeacherController::class, 'getStatistic']);
        Route::post('/revenue', [TeacherController::class, 'getRevenue']);
    }
);

// Learning
Route::prefix('/learning')
    ->middleware('verify.jwt.token')
    ->group(function () {
        Route::get('/course/{id}/content', [CourseController::class, 'getPurchasedCourseContent']);

        Route::post('/course/{id}/chapter/{chapterId}/lesson/{lessonId}/complete', [LessonController::class, 'markLessonCompleted']);
    }
);

// user
Route::middleware('verify.jwt.token')
    ->group(function () {
        Route::put('/profile', [UserController::class, 'updateProfile']);

        Route::get('/transaction-history', [TransactionController::class, 'getTransactionHistory']);

        Route::post('/change-password', [UserController::class, 'changePassword']);
    }
);

// Category
Route::get('/categories', [CategoryController::class, 'getAllCategories']);

// Level
Route::get('/levels', [LevelController::class, 'getAllLevel']);

// Course
Route::get('/course/{id}/information', [CourseController::class, 'getInfoCourse']);
Route::get('/course/{id}/statistic', [CourseController::class, 'getStatisticCourse']);
Route::get('/course/{id}/content', [CourseController::class, 'getContentCourse']);
Route::get('/course/{id}/overview', [CourseController::class, 'getOverviewCourse']);
Route::get('/course/{id}/teacher-information', [CourseController::class, 'getCourseTeacherInformation']);
Route::get('/course/{id}/reviews', [ReviewController::class, 'getReviewCourse']);
Route::get('/courses', [CourseController::class, 'getCourseByIds']);

// Payment
Route::get('/payment-methods', [PaymentMethodController::class, 'getAllPaymentMethod']);
Route::post('/checkout', [PaymentController::class, 'checkout']);
Route::post('/vouchers/check', [VoucherController::class, 'checkVoucher']);
Route::post('/confirm-webhook', [PaymentController::class, 'confirmWebhook']);
Route::get('/cancel', [PaymentController::class, 'cancel']);

Route::get('teacher/{id}', [TeacherController::class, 'getInfoTeacher']);

// Search 
Route::get('/courses/category/{id}', [SearchController::class, 'searchByCategory']);
Route::get('/courses/search', [SearchController::class, 'searchCourse']);

