<?php

namespace App\Repositories;

use App\Models\WithdrawMethod;
use App\Repositories\Interfaces\WithdrawMethodRepositoryInterface;

class WithdrawMethodRepository implements WithdrawMethodRepositoryInterface
{
    // 
    public function create(array $data){   
        return WithdrawMethod::create($data);

    }
}
