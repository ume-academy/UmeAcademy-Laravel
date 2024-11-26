<?php

namespace App\Services;

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
}
