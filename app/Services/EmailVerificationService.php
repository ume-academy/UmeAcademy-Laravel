<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Contracts\EmailVerificationInterface;
use App\Exceptions\Auth\UserNotFoundException;
use App\Exceptions\Auth\EmailAlreadyVerifiedException;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Exceptions\Auth\InvalidVerificationLinkException;
use App\Exceptions\Auth\TooManyVerificationRequestsException;
use App\Repositories\Interfaces\UserWalletRepositoryInterface;

class EmailVerificationService implements EmailVerificationInterface
{
    private const VERIFICATION_THROTTLE = 60; // 60 giây

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserWalletRepositoryInterface $userWalletRepository,
    ) {}

    public function verifyEmail(int $userId, string $hash)
    {
        try {
            $user = $this->userRepository->findById($userId);
            
            if (!$user) 
                throw new UserNotFoundException('Không tim thấy người dùng.');

            if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) 
                throw new InvalidVerificationLinkException('Link xác minh không hợp lệ.');

            if ($this->isEmailVerified($user)) 
                throw new EmailAlreadyVerifiedException('Email đã được xác minh.');

            // Đánh dấu email đã được xác minh
            $user->markEmailAsVerified($user);
            
            // Tạo ví sau khi người dùng xác minh email thành công
            $this->userWalletRepository->createWallet($user->id);

            // 
            return redirect()->away('https://umeacademy.online/login');

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function resendVerificationEmail(string $email)
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) 
            throw new UserNotFoundException('Không tìm thấy email.', 404);

        if ($this->isEmailVerified($user)) 
            throw new EmailAlreadyVerifiedException('Email đã được xác minh.', 409);

        if (!$this->canResendVerification($user->id)) 
            throw new TooManyVerificationRequestsException(
                'Vui lòng chờ ' . $this->getRemainingTime($user->id) . ' giây trước khi yêu cầu email xác minh mới.',
                429
            );

        $user->sendEmailVerificationNotification();

        $this->setCooldown($user->id);

        return response()->json(['message' => 'Đã gửi lại email xác minh tài khoản.']);
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
