<?php

namespace App\Repositories;

use App\Models\WithdrawalRequest;
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

    public function getAllRequest($perPage) {
        return WithdrawalRequest::paginate($perPage);
    }

    public function find(int $id) {
        return WithdrawalRequest::findOrFail($id);
    }

    public function updateStatus(int $id, $status) {
        $request = $this->find($id);

        $request->status = $status;
        $request->save();
        
        return $request;
    }
}
