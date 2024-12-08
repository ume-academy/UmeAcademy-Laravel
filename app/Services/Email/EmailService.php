<?php

namespace App\Services\Email;

use App\Contracts\EmailSenderInterface;
use App\Notifications\Auth\VerificationEmailNotification;
use App\Notifications\CertificateNotification;

class EmailService implements EmailSenderInterface
{
    public function sendVerificationEmail($user){
        $user->notify(new VerificationEmailNotification);
    }

    public function resendVerificationEmail(string $email)
    {
        return $email;
    }

    public function sendCertificate($user, $fileName){
        $user->notify(new CertificateNotification($fileName));
    }
    // public function sendPasswordResetEmail($user);
    // public function sendLoginAlertEmail($user, $location){

    // };
    // public function sendPaymentSuccessEmail($user, $order){

    // };
    // sendWithdrawalSuccessfulEmail
    // sendDepositlSuccessfulEmail
}