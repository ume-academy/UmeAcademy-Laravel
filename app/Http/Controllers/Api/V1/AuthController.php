<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\AuthService;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\Auth\LoginRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\Auth\RegisterRequest;
use App\Exceptions\Auth\EmailNotVerifiedException;
use App\Exceptions\Auth\InvalidCredentialsException;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService, 
    )
    {}

    public function register(RegisterRequest $request, string $type)
    {
        try {
            $data = $request->only(['fullname', 'email', 'password']);
    
            $user = $this->authService->register($type, $data);
    
            return response()->json([
                'message' => 'User registered successfully',
                'data' => $user
            ], 201);
    
            // return new UserResource($user);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function login(LoginRequest $request, string $type)
    {
        try {
            $credentials = $request->only(['email', 'password']);

            $token = $this->authService->login($type, $credentials);

            return $token;

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        } 
    }

    public function logout()
    {
        try {
            $refreshToken = request()->cookie('refresh_token');
            $acccessToken = JWTAuth::getToken();
            
            $data = $this->authService->logout($acccessToken, $refreshToken);
            return $data;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        } 
    }

    public function refreshTokens()
    {
        $refreshToken = request()->cookie('refresh_token');

        $token = $this->authService->refreshTokens($refreshToken);
        return $token;
    }

    // Other auth-related actions (login, logout, forgotPassword) omitted for brevity
}
