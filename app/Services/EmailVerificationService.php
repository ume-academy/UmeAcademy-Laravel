<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Contracts\EmailVerificationInterface;
use App\Exceptions\Auth\UserNotFoundException;
use App\Exceptions\Auth\EmailAlreadyVerifiedException;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Exceptions\Auth\InvalidVerificationLinkException;
use App\Exceptions\Auth\TooManyVerificationRequestsException;

class EmailVerificationService implements EmailVerificationInterface
{
    private const VERIFICATION_THROTTLE = 60; // 60 giây

    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function verifyEmail(int $userId, string $hash)
    {
        $user = $this->userRepository->findById($userId);
        
        if (!$user) 
            throw new UserNotFoundException('Không tim thấy người dùng.');

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) 
            throw new InvalidVerificationLinkException('Link xác minh không hợp lệ.');

        if ($this->isEmailVerified($user)) 
            throw new EmailAlreadyVerifiedException('Email đã được xác minh.');

        // Đánh dấu email đã được xác minh
        $user->markEmailAsVerified($user);
    }

    public function resendVerificationEmail(string $email)
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) 
            throw new UserNotFoundException('Không tìm thấy email.');

        if ($this->isEmailVerified($user)) 
            throw new EmailAlreadyVerifiedException('Email đã được xác minh.');

        if (!$this->canResendVerification($user->id)) 
            throw new TooManyVerificationRequestsException(
                'Vui lòng chờ ' . $this->getRemainingTime($user->id) . ' giây trước khi yêu cầu email xác minh mới.'
            );

        $user->sendEmailVerificationNotification();

        $this->setCooldown($user->id);
    }

    public function canResendVerification(int $userId)
    {
        return !Cache::has($this->getCacheKey($userId));
    }

    public function getRemainingTime(int $userId)
    {
        return Cache::get($this->getCacheKey($userId)) - now()->timestamp;
    }

    private function setCooldown(int $userId)
    {
        Cache::put(
            $this->getCacheKey($userId),
            now()->addSeconds(self::VERIFICATION_THROTTLE)->timestamp,
            self::VERIFICATION_THROTTLE
        );
    }

    private function getCacheKey(int $userId)
    {
        return 'email_verification_' . $userId;
    }

    public function isEmailVerified(object $user)
    {
        return $user->hasVerifiedEmail();
    }
}
