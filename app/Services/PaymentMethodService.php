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
        return $this->paymentMethodRepo->create($data);
    }

    public function updatePaymentMethod($id, $data) {
        return $this->paymentMethodRepo->update($id, $data);
    }

    public function deletePaymentMethod($id) {
        return $this->paymentMethodRepo->delete($id);
    }

    public function detailPaymentMethod($id) {
        return $this->paymentMethodRepo->find($id);
    }
}
