<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\EmailVerificationService;
use App\Http\Requests\Auth\ResendEmailRequest;

class EmailVerificationController
{
    public function __construct(
        private EmailVerificationService $emailVerificationService, 
    )
    {}
    public function verify(int $userId, string $hash)
    {
        try {
            return $this->emailVerificationService->verifyEmail($userId, $hash);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function resend(ResendEmailRequest $request)
    {
        try {
            return $this->emailVerificationService->resendVerificationEmail($request->email);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}
