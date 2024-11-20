<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\EmailVerificationService;
use App\Http\Requests\Auth\ResendEmailRequest;

class EmailVerification
{
    public function __construct(
        private EmailVerificationService $emailVerificationService, 
    )
    {}
    public function verify(int $userId, string $hash)
    {
        $this->emailVerificationService->verifyEmail($userId, $hash);
    }

    public function resend(ResendEmailRequest $request)
    {
        
    }
}
