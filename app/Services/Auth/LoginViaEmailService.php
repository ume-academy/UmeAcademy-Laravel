<?php

namespace App\Services\Auth;

use App\Contracts\LoginInterface;
use App\Contracts\TokenInterface;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Contracts\EmailVerificationInterface;
use App\Exceptions\Auth\EmailNotVerifiedException;
use App\Exceptions\Auth\InvalidCredentialsException;
use App\Repositories\Interfaces\UserRepositoryInterface;

class LoginViaEmailService implements LoginInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository, 
        private EmailVerificationInterface $emailVerification, 
        private TokenInterface $token, 
    ) {}

    public function loginViaEmail(array $credentials)
    {
        if ( !$token = JWTAuth::attempt($credentials)) 
            throw new InvalidCredentialsException('Tài khoản hoặc mật khẩu không chính xác.');

        $user = $this->userRepository->findByEmail($credentials['email']);

        // Kiểm tra xem email đã được xác minh chưa
        if ( !$this->emailVerification->isEmailVerified($user)) 
            throw new EmailNotVerifiedException('Email chưa được xác minh.');

        return $this->token->respondWithToken($token);
    }
}
