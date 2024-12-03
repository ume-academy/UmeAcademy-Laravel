<?php

namespace App\Services;

use App\Repositories\Interfaces\FeePlatformRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class FeeService
{
    public function __construct(
        private FeePlatformRepositoryInterface $feePlatformRepo
    ){}

    public function update($id, $data) {
        return $this->feePlatformRepo->updateFee($id, $data);
    }

    public function get($id) {
        return $this->feePlatformRepo->getById($id);
    }
}
