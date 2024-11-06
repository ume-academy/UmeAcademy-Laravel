<?php

namespace App\Http\Controllers\Voucher;

use App\Contracts\CreateVoucherServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Voucher\StoreVoucherRequest;
use App\Http\Resources\Voucher\VoucherResource;

class VoucherController extends Controller
{
    public function __construct(
        private CreateVoucherServiceInterface $createVoucherService
    ){}

    public function createVoucher(StoreVoucherRequest $req, $id) {
        try {
            $data = $req->only([
                'code',
                'quantity',
                'discount',
                'start_date',
                'end_date',
            ]);
            $data['course_id'] = $id;

            $voucher = $this->createVoucherService->createVoucher($data);

            return new VoucherResource($voucher);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
