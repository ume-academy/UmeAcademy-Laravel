<?php

namespace App\Repositories;

use App\Models\VoucherUsage;
use App\Repositories\Interfaces\VoucherUsageRepositoryInterface;

class VoucherUsageRepository implements VoucherUsageRepositoryInterface
{
    public function create(array $data)
    {
        return VoucherUsage::create($data);
    }

    public function getByTransaction($id) {
        return VoucherUsage::where('transaction_id', $id)->first();
    }

    public function updateStatus(int $id, $status) {
        $voucherUsage = VoucherUsage::findOrFail($id);

        $voucherUsage->status = $status;
        $voucherUsage->save();
    }

    public function delete(int $id) {
        $voucherUsage = VoucherUsage::findOrFail($id);

        $voucherUsage->delete();
    }
}
