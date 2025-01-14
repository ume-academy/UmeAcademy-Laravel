<?php

namespace App\Contracts;

interface EmailSenderInterface
{
    public function sendVerificationEmail($user);
    // public function sendPasswordResetEmail($user);
    // public function sendLoginAlertEmail($user, $location);
    // public function sendPaymentSuccessEmail($user, $order);
}