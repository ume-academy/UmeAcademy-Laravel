<?php

namespace App\Services;

use App\Repositories\Interfaces\RefundRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class RefundService
{
    public function __construct(
        private RefundRepositoryInterface $refundRepo
    ){}

    public function getAllRefundRequest($perPage, $status) {
        return $this->refundRepo->getAll($perPage, $status);
    }

    public function updateStatus($id, $status) {
        $request = $this->refundRepo->find($id);

        return $this->refundRepo->updateStatus($id, $status);
    }
}
