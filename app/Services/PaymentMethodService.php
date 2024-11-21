<?php

namespace App\Services;

use App\Repositories\Interfaces\PaymentMethodRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class PaymentMethodService
{
    public function __construct(
        private PaymentMethodRepositoryInterface $paymentMethodRepo
    ){}

    public function getAllPaymentMethod() {
        return $this->paymentMethodRepo->getAll();
    }

    public function createPaymentMethod($data) {
        $user = JWTAuth::parseToken()->authenticate();

        if(!$user || !$user->hasRole('admin')) {
            throw new AuthorizationException('Unauthorized');
        }

        return $this->paymentMethodRepo->create($data);
    }

    public function updatePaymentMethod($id, $data) {
        $user = JWTAuth::parseToken()->authenticate();

        if(!$user || !$user->hasRole('admin')) {
            throw new AuthorizationException('Unauthorized');
        }

        return $this->paymentMethodRepo->update($id, $data);
    }

    public function deletePaymentMethod($id) {
        $user = JWTAuth::parseToken()->authenticate();

        if(!$user || !$user->hasRole('admin')) {
            throw new AuthorizationException('Unauthorized');
        }

        return $this->paymentMethodRepo->delete($id);
    }
}
