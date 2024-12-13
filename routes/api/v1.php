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
use App\Http\Controllers\Api\V1\PermissionController;
use App\Http\Controllers\Api\V1\RefundController;
use App\Http\Controllers\Api\V1\RoleController;
use App\Http\Controllers\Api\V1\StudentController;
use App\Models\Certificate;
use App\Http\Controllers\Api\V1\WithdrawRequestController;
use App\Http\Controllers\Api\V1\WebhookSepayController;

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

    // Wallet
    Route::get('/wallet-balance', [StudentController::class, 'getWalletBalance']);
    Route::get('/wallet-transaction', [StudentController::class, 'getWalletTransaction']);

    // Student refund request
    Route::post('/refund/{transactionCode}', [RefundController::class, 'createRefundRequest']);
});


// Admin
Route::prefix('admin')
    ->middleware('verify.jwt.token')
    ->group(function () {
        Route::post('/check', [UserController::class, 'checkAdmin']);

        // Category
        Route::post('/categories', [CategoryController::class, 'storeCategories'])->middleware('can:create-category');
        Route::get('/categories/{id}', [CategoryController::class, 'getCategory'])->middleware('can:view-category');
        Route::put('/categories/{id}', [CategoryController::class, 'updateCategory'])->middleware('can:update-category');
        Route::delete('/categories/{id}', [CategoryController::class, 'deleteCategory'])->middleware('can:delete-category');

        // Fee
        Route::put('/fee/{id}', [FeeController::class, 'update'])->middleware('can:update-fee');
        Route::get('/fee/{id}', [FeeController::class, 'get'])->middleware('can:view-fee');

        Route::get('/fee/teacher/{id}', [FeeController::class, 'getFeeTeacher'])->middleware('can:view-fee-teacher');
        Route::put('/fee/teacher/{id}', [FeeController::class, 'updateFeeTeacher'])->middleware('can:update-fee-teacher');

        // Payment method
        Route::post('/payment-methods', [PaymentMethodController::class, 'createPaymentMethod'])->middleware('can:create-payment-method');
        Route::put('/payment-methods/{id}', [PaymentMethodController::class, 'updatePaymentMethod'])->middleware('can:update-payment-method');
        Route::delete('/payment-methods/{id}', [PaymentMethodController::class, 'deletePaymentMethod'])->middleware('can:delete-payment-method');
        Route::get('/payment-methods/{id}', [PaymentMethodController::class, 'detailPaymentMethod'])->middleware('can:view-payment-method');

        Route::get('/users', [UserController::class, 'getListUser'])->middleware('can:view-users');
        Route::get('/user/{id}', [UserController::class, 'getUser'])->middleware('can:view-user');
        Route::get('/teachers', [UserController::class, 'getListTeacher'])->middleware('can:view-teachers');

        Route::post('user/{id}/lock', [UserController::class, 'lock'])->middleware('can:lock-user');
        Route::post('user/{id}/unlock', [UserController::class, 'unlock'])->middleware('can:unlock-user');

        Route::get('/teacher/{id}/statistic', [TeacherController::class, 'getStatisticOfTeacher'])->middleware('can:view-teacher-statistic');

        Route::post('/course/{id}/approval', [CourseController::class, 'approval'])->middleware('can:approve-course');

        Route::get('/courses', [CourseController::class, 'getAllCourse'])->middleware('can:view-courses');
        Route::get('/course/{id}', [CourseController::class, 'getDetailCourse'])->middleware('can:view-course');

        // Transaction 
        Route::get('/transactions', [TransactionController::class, 'getAllTransaction'])->middleware('can:view-transactions');

        // Withdraw request
        Route::get('/withdraw-request', [WithdrawMethodController::class, 'getWithdrawRequest'])->middleware('can:view-withdraw-requests');
        Route::put('/withdraw-request/{id}', [WithdrawMethodController::class, 'updateStatus'])->middleware('can:update-withdraw-status');

        // Wallet transaction 
        Route::get('/student/{id}/wallet-transactions', [StudentController::class, 'getWalletTransactionByStudent'])->middleware('can:view-student-wallet-transactions');
        Route::get('/student/{id}/purchased-courses', [StudentController::class, 'getPurchasedCoursesByStudent'])->middleware('can:view-student-purchased-courses');

        Route::get('/teacher/{id}/wallet-transactions', [TeacherController::class, 'getWalletTransactionByTeacher'])->middleware('can:view-teacher-wallet-transactions');
        Route::get('/teacher/{id}/courses', [TeacherController::class, 'getCoursesByTeacher'])->middleware('can:view-teacher-courses');

        // Role
        Route::get('/roles', [RoleController::class, 'getAllRole'])->middleware('can:view-roles');
        Route::post('/roles', [RoleController::class, 'createRole'])->middleware('can:create-role');
        Route::get('/roles/{id}', [RoleController::class, 'getRole'])->middleware('can:view-role');
        Route::put('/roles/{id}', [RoleController::class, 'updateRole'])->middleware('can:update-role');
        Route::delete('/roles/{id}', [RoleController::class, 'deleteRole'])->middleware('can:delete-role');

        Route::post('/roles/{id}/permissions', [RoleController::class, 'assignPermission'])->middleware('can:assign-permissions');
        Route::get('/roles/{id}/permissions', [RoleController::class, 'getPermissionOfRole'])->middleware('can:view-role-permissions');

        // Permission
        Route::get('/permissions', [PermissionController::class, 'getAllPermission'])->middleware('can:view-permissions');

        // User system
        Route::get('/user-system', [UserController::class, 'getListUserSystem'])->middleware('can:view-user-system');
        Route::post('/user-system/{id}/roles', [UserController::class, 'assignRole'])->middleware('can:assign-role');
        Route::post('/user-system', [UserController::class, 'createUserSystem'])->middleware('can:create-user-system');

        // Voucher
        Route::post('/voucher', [VoucherController::class, 'createVoucherSystem'])->middleware('can:create-voucher');
        Route::get('/voucher', [VoucherController::class, 'getVoucherSystem'])->middleware('can:view-vouchers');

        // review refund request (xét duyệt yêu cầu hoàn tiền)
        Route::get('/refund-request', [RefundController::class, 'getAllRefundRequest'])->middleware('can:view-refund-requests');
        
        Route::post('/refund/{transactionCode}/review', [RefundController::class, 'reviewRefundRequest'])->middleware('can:update-refund-status');
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
        Route::put('/course/{id}/chapter/{chapterId}/lesson/{lessonId}/videos', [LessonController::class, 'updateVideo']);

        Route::post('/course/{id}/chapter/{chapterId}/lesson/{lessonId}/resources', [LessonController::class, 'createResource']);

        Route::post('/course/{id}/vouchers', [VoucherController::class, 'createVoucher']);

        Route::get('/course/{id}/vouchers', [VoucherController::class, 'getVouchersOfCourse']);

        Route::get('/course/{id}', [CourseController::class, 'getCourse']);
        Route::put('/course/{id}', [CourseController::class, 'updateCourse']);

        Route::put('/course/{id}/target-course', [CourseController::class, 'updateTargetCourse']);

        Route::post('/course/{id}/course-approval-request', [CourseController::class, 'requestApprovalCourse']);

        Route::get('/wallet-balance', [TeacherController::class, 'getWalletBalance']);
        Route::get('/wallet-transaction', [TeacherController::class, 'getWalletTransaction']);

        // Withdraw
        Route::post('/withdraw-method', [WithdrawMethodController::class, 'addWithdrawMethod']);
        Route::get('/withdraw-method', [WithdrawMethodController::class, 'getWithdrawMethod']);
        Route::put('/withdraw-method/{id}', [WithdrawMethodController::class, 'updateWithdrawMethod']);

        // Create a withdrawal request
        Route::post('/withdraw-requests', [WithdrawRequestController::class, 'create']);

        // History withdrawal
        Route::get('/withdraw-histories', [WithdrawRequestController::class, 'history']); 


        Route::get('/course/{id}/students', [CourseController::class, 'getStudentsOfCourse']);

        Route::get('/statistic', [TeacherController::class, 'getStatistic']);
        Route::get('/revenue', [TeacherController::class, 'getRevenue']);

        Route::get('/profile', [TeacherController::class, 'getProfile']);
        Route::put('/profile', [TeacherController::class, 'updateProfile']);


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

        Route::post('/course/{id}/add-wishlist', [CourseController::class, 'addWishlist']);
        Route::post('/course/{id}/remove-wishlist', [CourseController::class, 'removeWishlist']);
        Route::get('/course/wishlist', [CourseController::class, 'getWishlist']);

        // Review
        Route::post('/course/{id}/reviews', [ReviewController::class, 'createReviewCourse']);
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
Route::get('/courses', [CourseController::class, 'getAllCoursePublic']);
Route::get('/teacher/{id}', [TeacherController::class, 'getInformationTeacher']);
Route::get('/course-price', [CourseController::class, 'coursePrice']);

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

// Bank 
Route::get('/banks', [WithdrawMethodController::class, 'getBanks']);

// Auto Update Status Withdraw Request
Route::post('/webhook-sepay', [WebhookSepayController::class, 'autoUpdateStatusWithdrawRequest']);
