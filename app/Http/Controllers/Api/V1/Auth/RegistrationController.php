<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Contracts\RegistrationInterface;
use App\Http\Requests\Auth\RegisterRequest;

class RegistrationController extends Controller
{
    public function __construct(
        private RegistrationInterface $registration, 
    ) {}

    public function registerWithEmail(RegisterRequest $request)
    {
        try {
            $data = $request->only(['fullname', 'email', 'password']);

            $user = $this->registration->registerViaEmail($data);

            return new UserResource($user);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
