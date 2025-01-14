<?php

namespace App\Contracts;

interface EmailVerificationInterface
{
    public function verifyEmail(int $userId, string $hash);
    public function resendVerificationEmail(string $email);
    public function canResendVerification(int $userId);
    public function getRemainingTime(int $userId);
    public function isEmailVerified(object $user);
}