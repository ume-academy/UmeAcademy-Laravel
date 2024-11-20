<?php

namespace App\Services;

use App\Services\TokenService;
use App\Factories\LoginFactory;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Factories\RegistrationFactory;
use Illuminate\Support\Facades\Cookie;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthService
{
    public function __construct(
        private RegistrationFactory $registrationFactory, 
        private LoginFactory $loginFactory, 
        private TokenService $tokenService, 
    )
    {}

    public function register(string $type, array $data)
    {
        // Tạo đối tượng dựa trên loại phương thức đăng ký
        $registerMethod = $this->registrationFactory->createRegistrationMethod($type);
        
        return $registerMethod->register($data);
    }

    public function login(string $type, array $credentials)
    {
        // Tạo đối tượng dựa trên loại phương thức đăng nhập
        $loginMethod = $this->loginFactory->createLoginMethod($type);

        // Đăng nhập và trả về người dùng
        $user = $loginMethod->login($credentials);

        // Tạo token sau khi đăng nhập thành công
        return $this->tokenService->generateTokens($user);
    }

    public function logout(string $accessToken, string $refreshToken)
    {
        if (empty($refreshToken)) {
            return ['error' => 'Refresh token không được cung cấp.'];
        }
        try {

            // Giải mã refresh token để lấy user_id và device_id
            $decoded = JWTAuth::setToken($refreshToken)->getPayload();
            $userId = $decoded->get('sub');
            // $deviceId = $decoded->get('device_id'); // Giả sử token có chứa thông tin device_id

            // Xóa refresh token khỏi database và đưa vào blacklist
            $this->tokenService->revokeTokens($refreshToken);

            // Xóa cookie chứa refresh token
            Cookie::forget('refresh_token');

            // Vô hiệu hóa access token
            JWTAuth::invalidate($accessToken);
            return ['message' => 'Đã đăng xuất thành công.'];
        } catch (JWTException $e) {
            return ['error' => 'Đăng xuất thất bại'];
        }

    }

    public function refreshTokens(string $refreshToken)
    {
        if (!$refreshToken) {
            return ['error' => 'Refresh token không được cung cấp. Vui lòng đăng nhập lại.'];
        }

        return $this->tokenService->refreshTokens($refreshToken);
    }

    // Other auth-related methods (login, logout, forgotPassword) omitted for brevity
}