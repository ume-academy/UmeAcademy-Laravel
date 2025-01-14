<?php

namespace App\Repositories;

use App\Models\PaymentMethod;
use App\Repositories\Interfaces\PaymentMethodRepositoryInterface;

class PaymentMethodRepository implements PaymentMethodRepositoryInterface
{
    public function getAll() {
        return PaymentMethod::get();
    }

    public function create(array $data)
    {
        return PaymentMethod::create($data);
    }

    public function update(int $id, array $data)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        return $paymentMethod->update($data);
    }

    public function delete(int $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        return $paymentMethod->delete();
    }

    public function find(int $id) {
        return PaymentMethod::findOrFail($id);
    }
}
