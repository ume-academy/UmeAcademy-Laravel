<?php

namespace App\Repositories;

use App\Models\Voucher;
use App\Repositories\Interfaces\VoucherRepositoryInterface;

class VoucherRepository implements VoucherRepositoryInterface
{
    public function create(array $data) {
        return Voucher::create($data);
    }

    public function getAllByCourse(int $id) {
        return Voucher::where('course_id', $id)->orderBy('created_at', 'desc')->get();
    }

    public function getByCode($code) {
        return Voucher::whereRaw('BINARY `code` = ?', [$code])->first();
    }

    public function find(int $id) {
        return Voucher::findOrFail($id);
    }

    public function getAllVoucher($perPage) {
        return Voucher::where('creator_type', 'admin')->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getById($id) {
        return Voucher::findOrFail($id);
    }

    public function update($id, $data) {
        $voucher = Voucher::findOrFail($id);

        return $voucher->update($data);
    }

    public function delete($id) {
        $voucher = Voucher::findOrFail($id);
        
        return $voucher->delete();
    }
}
