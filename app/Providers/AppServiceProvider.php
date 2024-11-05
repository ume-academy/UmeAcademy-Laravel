<?php

namespace App\Providers;

use App\Contracts\CreateCourseServiceInterface;
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
use App\Services\Course\CreateCourseService;
use App\Services\Teacher\TeacherRegistrationService;

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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
