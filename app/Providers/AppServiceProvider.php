<?php

namespace App\Providers;

use App\Contracts\CreateChapterServiceInterface;
use App\Contracts\CreateCourseServiceInterface;
use App\Contracts\CreateLessonServiceInterface;
use App\Contracts\CreateVideoServiceInterface;
use App\Contracts\CreateVoucherServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\Contracts\LoginInterface;
use App\Contracts\TokenInterface;
use App\Services\Auth\TokenService;
use App\Services\Mail\EmailService;
use App\Contracts\EmailSenderInterface;
use App\Contracts\RegistrationInterface;
use App\Services\Auth\LoginViaEmailService;
use App\Contracts\EmailVerificationInterface;
use App\Contracts\RefreshTokenInterface;
use App\Contracts\TeacherRegistrationInterface;
use App\Services\Auth\EmailVerificationService;
use App\Services\Auth\RefreshTokenService;
use App\Services\Auth\RegistrationViaEmailService;
use App\Services\Chapter\CreateChapterService;
use App\Services\Course\CreateCourseService;
use App\Services\Lesson\CreateVideoService;
use App\Services\Lesson\CreateLessonService;
use App\Services\Teacher\TeacherRegistrationService;
use App\Services\Voucher\CreateVoucherService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(EmailSenderInterface::class, EmailService::class);
        $this->app->bind(RegistrationInterface::class, RegistrationViaEmailService::class);
        $this->app->bind(EmailVerificationInterface::class, EmailVerificationService::class);
        $this->app->bind(LoginInterface::class, LoginViaEmailService::class);
        $this->app->bind(TokenInterface::class, TokenService::class);
        $this->app->bind(RefreshTokenInterface::class, RefreshTokenService::class);
        $this->app->bind(TeacherRegistrationInterface::class, TeacherRegistrationService::class);
        $this->app->bind(CreateCourseServiceInterface::class, CreateCourseService::class);
        $this->app->bind(CreateChapterServiceInterface::class, CreateChapterService::class);
        $this->app->bind(CreateLessonServiceInterface::class, CreateLessonService::class);
        $this->app->bind(CreateVideoServiceInterface::class, CreateVideoService::class);
        $this->app->bind(CreateVoucherServiceInterface::class, CreateVoucherService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
