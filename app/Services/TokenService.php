<?php

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cache;
use App\Contracts\TokenServiceInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\TokenRepositoryInterface;
use Illuminate\Support\Facades\File;

class TokenService implements TokenServiceInterface 
{
    public function __construct(
        protected TokenRepositoryInterface $tokenRepository, 
        protected UserRepositoryInterface $userRepository, 
        ) 
        {}
        
    public function generateTokens(object $user) 
    {
            $accessToken = JWTAuth::fromUser($user);
            $refreshToken = $this->createRefreshToken($user);
            
            return [
                'access_token' => $accessToken, 
                'refresh_token' => $refreshToken, 
                'token_type' => 'bearer', 
                'expires_in' => config('jwt.ttl') * 60, 
        ];
    }

    private function createRefreshToken(object $user)
    {
        $refreshTokenTTL = time() + config('jwt.refresh_ttl'); // second

        // Tạo refresh token
        $refreshToken = JWTAuth::claims(['exp' => $refreshTokenTTL])->fromUser($user);

        $this->tokenRepository->storeRefreshToken($user->id, $refreshToken);

        return $refreshToken;
    }

    public function revokeTokens(string $refreshToken) 
    {
        // Đưa refreshToken vào blacklist
        $this->addBlacklistExpiredRefreshToken($refreshToken);

        // Xóa refreshToken khỏi DB
        $this->tokenRepository->deleteRefreshToken($refreshToken);
    }

    public function refreshTokens(string $refreshToken) 
    {
//         $cacheFiles = File::files(storage_path('framework/cache/data'));
//         $keys = Cache::store('file')->keys('*');
// return($keys);
// return $this->isRefreshTokenBlacklisted("00121");
        // Kiểm tra xem refresh_token có trong blacklist không
        if ($this->isRefreshTokenBlacklisted($refreshToken)) {
            /* 
                (chưa làm)
                Gửi cảnh báo đăng nhập và khóa tài khoản hoặc không cho phép đăng nhập.
                Lưu trữ lại địa chỉ IP, thông tin thiết bị, vị trí, thời gian, ...
            */
            return ['error' => 'Refresh token này có trong backlist.'];
        }

        // Giải mã token và lấy userId
        $decoded = JWTAuth::setToken($refreshToken)->getPayload();
        $userId = $decoded->get('sub');
        
        // Tìm User dựa trên userId
        $user = $this->userRepository->findById($userId);
        
        // Kiểm tra refresh token có hợp lệ không
        $storedToken = $this->tokenRepository->getRefreshToken($userId);
        if ($storedToken !== $refreshToken) {
            return ['error' => 'Refresh token không hợp lệ.'];
            // tra ve Exception
        }

        // Cho refresh token cũ vào blacklist
        $this->addBlacklistExpiredRefreshToken($refreshToken);

        // Tạo access token và refresh token mới
        $newTokens = $this->generateTokens($user);

        // Cập nhật refresh token mới vào database
        $this->tokenRepository->storeRefreshToken($userId, $newTokens['refresh_token']);

        return $newTokens;
    }

    public function addBlacklistExpiredRefreshToken($refreshToken) 
    {
        // Tự động xóa refresh_token trong blacklist sau 90 ngày
        $expiresAt = now()->addDays(90); 

        $cacheKey = $refreshToken;
        // $cacheKey = 'user_id_' . $userId . $refreshToken;

        // Lưu refresh_token vào blacklist trong cache
        Cache::put($cacheKey, true, $expiresAt);
    }

    public function isRefreshTokenBlacklisted($refreshToken) 
    {
        // $cacheKey = 'user_id_' . $userId . $refreshToken;
    
        return Cache::has($refreshToken);
    }
}
