<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\LoginInterface;
use App\Contracts\TokenInterface;
use App\Services\Auth\TokenService;
use App\Services\Mail\EmailService;
use App\Contracts\EmailSenderInterface;
use App\Contracts\RegistrationInterface;
use App\Services\Auth\LoginViaEmailService;
use App\Contracts\EmailVerificationInterface;
use App\Services\Auth\EmailVerificationService;
use App\Services\Auth\RegistrationViaEmailService;

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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}