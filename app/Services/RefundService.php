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

    public function getAllRefundRequest($perPage) {
        $user = JWTAuth::parseToken()->authenticate();

        if(!$user || !$user->hasRole('admin')) {
            throw new AuthorizationException('Unauthorized');
        }

        return $this->refundRepo->getAll($perPage);
    }

    public function updateStatus($id, $status) {
        $user = JWTAuth::parseToken()->authenticate();

        if(!$user || !$user->hasRole('admin')) {
            throw new AuthorizationException('Unauthorized');
        }

        $request = $this->refundRepo->find($id);

        if($request->status == 1 || $request->status == 0) {
            throw new \Exception('Yêu cầu đã được phê duyệt');
        }

        return $this->refundRepo->updateStatus($id, $status);
    }
}
