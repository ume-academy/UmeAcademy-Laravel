<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Voucher\StoreVoucherRequest;
use App\Http\Resources\Voucher\VoucherResource;
use App\Services\VoucherService;

class VoucherController extends Controller
{
    public function __construct(
        private VoucherService $voucherService,
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

            $voucher = $this->voucherService->createVoucher($data);

            return new VoucherResource($voucher);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAllVoucher($id) {
        try {
            $vouchers = $this->voucherService->getAllVoucher($id);

            return VoucherResource::collection($vouchers);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}