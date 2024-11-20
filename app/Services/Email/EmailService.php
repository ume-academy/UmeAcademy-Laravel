<?php

namespace App\Services\Email;

use App\Contracts\EmailSenderInterface;
use App\Notifications\Auth\VerificationEmailNotification;

class EmailService implements EmailSenderInterface
{
    public function sendVerificationEmail($user){
        $user->notify(new VerificationEmailNotification);
    }

    public function resendVerificationEmail(string $email)
    {
        return $email;
    }

    // public function sendPasswordResetEmail($user);
    // public function sendLoginAlertEmail($user, $location){

    // };
    // public function sendPaymentSuccessEmail($user, $order){

    // };
    // sendWithdrawalSuccessfulEmail
    // sendDepositlSuccessfulEmail
}