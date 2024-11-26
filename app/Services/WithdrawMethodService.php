<?php

namespace App\Services;

use App\Repositories\Interfaces\WithdrawMethodRepositoryInterface;
use App\Traits\ValidationTrait;
use Tymon\JWTAuth\Facades\JWTAuth;

class WithdrawMethodService
{
    use ValidationTrait;
    // 
    public function __construct(
        private WithdrawMethodRepositoryInterface $withdrawMethodRepository
    ) {
    }
    public function addPaymentInfomation(array $data)
    {
        $teacher = $this->validateTeacher();
        // $user = JWTAuth::parseToken()->authenticate();
        if (!$teacher) {
            throw new \Exception("Teacher validation failed."); // Adjust as needed
        }
        //Lấy id của teacher đang đăng nhập
        return $this->withdrawMethodRepository->create($data);
    }
    public function getWithdrawMethod(int $teacherId){
        $teacher = $this->validateTeacher();
        if (!$teacher) {
            throw new \Exception("Teacher validation failed."); // Adjust as needed
            }
            return $this->withdrawMethodRepository->getWithdrawMethod($teacherId);
    }
}
