<?php

namespace App\Services;

use App\Models\WithdrawMethod;
use App\Repositories\Interfaces\WithdrawMethodRepositoryInterface;
use App\Traits\ValidationTrait;
use Tymon\JWTAuth\Facades\JWTAuth;

class WithdrawMethodService
{
    use ValidationTrait;

    public function __construct(
        private WithdrawMethodRepositoryInterface $withdrawMethodRepo
    ) {}

    public function addWithdrawMethod(array $data)
    {
        $teacher = $this->validateTeacher();
        $data['teacher_id'] = $teacher->id;
        
        return $this->withdrawMethodRepo->create($data);
    }
    
    public function getWithdrawMethod(){
        $teacher = $this->validateTeacher();
        
        return $this->withdrawMethodRepo->getWithdrawMethod($teacher->id);
    }

    public function getBanks() {
        return $this->withdrawMethodRepo->getAllBank();
    }

    public function updateWithdrawMethod($id, $data) {
        $teacher = $this->validateTeacher();
        $data['teacher_id'] = $teacher->id;

        $method = $this->getWithdrawMethod();
        
        if($method->id != $id) {
            throw new \Exception('Bạn không có quyền sửa tài khoản này!', 403);
        }

        return $this->withdrawMethodRepo->update($id, $data);
    }
}
