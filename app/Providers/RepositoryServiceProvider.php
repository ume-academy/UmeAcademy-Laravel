<?php

namespace App\Providers;


use App\Models\Wallet;
use App\Repositories\UserRepository;
use App\Repositories\LevelRepository;
use App\Repositories\TokenRepository;
use App\Repositories\VideoRepository;
use App\Repositories\CourseRepository;
use App\Repositories\LessonRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\ChapterRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\VoucherRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CategoryRepository;
use App\Repositories\UserWalletRepository;
use App\Repositories\FeePlatformRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\RefreshTokenRepository;
use App\Repositories\VoucherUsageRepository;
use App\Repositories\PaymentMethodRepository;
use App\Repositories\TeacherWalletRepository;
use App\Repositories\CourseApprovalRepository;
use App\Repositories\WithdrawMethodRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\TeacherWalletTransactionRepository;
use App\Repositories\Interfaces\LevelRepositoryInterface;
use App\Repositories\Interfaces\TokenRepositoryInterface;
use App\Repositories\Interfaces\VideoRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\LessonRepositoryInterface;
use App\Repositories\Interfaces\ReviewRepositoryInterface;
use App\Repositories\Interfaces\ChapterRepositoryInterface;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use App\Repositories\Interfaces\VoucherRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\UserWalletRepositoryInterface;
use App\Repositories\Interfaces\FeePlatformRepositoryInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\Interfaces\VoucherUsageRepositoryInterface;
use App\Repositories\Interfaces\PaymentMethodRepositoryInterface;
use App\Repositories\Interfaces\TeacherWalletRepositoryInterface;
use App\Repositories\Interfaces\CourseApprovalRepositoryInterface;
use App\Repositories\Interfaces\RefundRepositoryInterface;
use App\Repositories\Interfaces\ResourceRepositoryInterface;
use App\Repositories\Interfaces\StudentWalletRepositoryInterface;
use App\Repositories\Interfaces\StudentWalletTransactionRepositoryInterface;
use App\Repositories\Interfaces\WithdrawMethodRepositoryInterface;
use App\Repositories\Interfaces\TeacherWalletTransactionRepositoryInterface;
use App\Repositories\RefundRepository;
use App\Repositories\ResourceRepository;
use App\Repositories\StudentWalletRepository;
use App\Repositories\StudentWalletTransactionRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(TeacherRepositoryInterface::class, TeacherRepository::class);
        $this->app->bind(TeacherWalletRepositoryInterface::class, TeacherWalletRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(ChapterRepositoryInterface::class, ChapterRepository::class);
        $this->app->bind(LessonRepositoryInterface::class, LessonRepository::class);
        $this->app->bind(VideoRepositoryInterface::class, VideoRepository::class);
        $this->app->bind(VoucherRepositoryInterface::class, VoucherRepository::class);
        $this->app->bind(ReviewRepositoryInterface::class, ReviewRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(FeePlatformRepositoryInterface::class, FeePlatformRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(VoucherUsageRepositoryInterface::class, VoucherUsageRepository::class);
        $this->app->bind(TeacherWalletTransactionRepositoryInterface::class, TeacherWalletTransactionRepository::class);
        $this->app->bind(TokenRepositoryInterface::class, TokenRepository::class);
        $this->app->bind(WithdrawMethodRepositoryInterface::class, WithdrawMethodRepository::class);
        $this->app->bind(CourseApprovalRepositoryInterface::class, CourseApprovalRepository::class);
        $this->app->bind(PaymentMethodRepositoryInterface::class, PaymentMethodRepository::class);
        $this->app->bind(LevelRepositoryInterface::class, LevelRepository::class);
        $this->app->bind(UserWalletRepositoryInterface::class, UserWalletRepository::class);
        $this->app->bind(ResourceRepositoryInterface::class, ResourceRepository::class);
        $this->app->bind(StudentWalletRepositoryInterface::class, StudentWalletRepository::class);
        $this->app->bind(StudentWalletTransactionRepositoryInterface::class, StudentWalletTransactionRepository::class);
        $this->app->bind(RefundRepositoryInterface::class, RefundRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
