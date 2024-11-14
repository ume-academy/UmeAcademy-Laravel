<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\ChapterRepository;
use App\Repositories\CourseRepository;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\ChapterRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\LessonRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\RefreshTokenRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\RefreshTokenRepositoryInterface;
use App\Repositories\Interfaces\ReviewRepositoryInterface;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use App\Repositories\Interfaces\TeacherVoucherRepositoryInterface;
use App\Repositories\Interfaces\TeacherWalletRepositoryInterface;
use App\Repositories\Interfaces\VideoRepositoryInterface;
use App\Repositories\LessonRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\TeacherVoucherRepository;
use App\Repositories\TeacherWalletRepository;
use App\Repositories\VideoRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(RefreshTokenRepositoryInterface::class, RefreshTokenRepository::class);
        $this->app->bind(TeacherRepositoryInterface::class, TeacherRepository::class);
        $this->app->bind(TeacherWalletRepositoryInterface::class, TeacherWalletRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(ChapterRepositoryInterface::class, ChapterRepository::class);
        $this->app->bind(LessonRepositoryInterface::class, LessonRepository::class);
        $this->app->bind(VideoRepositoryInterface::class, VideoRepository::class);
        $this->app->bind(TeacherVoucherRepositoryInterface::class, TeacherVoucherRepository::class);
        $this->app->bind(ReviewRepositoryInterface::class, ReviewRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
