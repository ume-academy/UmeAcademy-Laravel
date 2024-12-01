<?php

namespace App\Repositories;

use App\Models\WithdrawMethod;
use App\Repositories\Interfaces\WithdrawMethodRepositoryInterface;

class WithdrawMethodRepository implements WithdrawMethodRepositoryInterface
{
    public function create(array $data){   
        return WithdrawMethod::create($data);
    }
    
    public function getWithdrawMethod(int $teacherId){
        return WithdrawMethod::where('teacher_id', $teacherId)->first();

    }

    public function getAllBank() {
        return WithdrawMethod::getEnumValues('name_bank');
    }

    public function update($id, $data) {
        $method = WithdrawMethod::findOrFail($id);

        $method->update($data);
        return $method;
    }
}
