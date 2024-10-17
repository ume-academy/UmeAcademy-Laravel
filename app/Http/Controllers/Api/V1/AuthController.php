<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use App\Services\Auth\RegisterUserService;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    protected $registerUserService;

    public function __construct(RegisterUserService $registerUserService)
    {
        $this->registerUserService = $registerUserService;
    }

    public function register(RegisterRequest $registerRequest)
    {
        try {
            // Create User
            $user = $this->registerUserService->register($registerRequest->only(['name', 'email', 'password']));

            event(new Registered($user));

            return response()->json($user,
                201
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'mesage' => $e->getMessage()
            ]);
        }
    }
}
