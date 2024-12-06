<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Voucher\CheckVoucherRequest;
use App\Http\Requests\Voucher\StoreVoucherRequest;
use App\Http\Resources\Voucher\VoucherResource;
use App\Services\VoucherService;
use Illuminate\Database\QueryException;

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
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) { 
                return response()->json(['error' => "Code đã tồn tại"], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getVouchersOfCourse($id) {
        try {
            $vouchers = $this->voucherService->getVouchersOfCourse($id);

            return VoucherResource::collection($vouchers);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function checkVoucher(CheckVoucherRequest $req) {
        try {
            $data = $req->only(['code', 'course_id']);

            $voucher = $this->voucherService->checkVoucher($data);

            return new VoucherResource($voucher);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createVoucherSystem(StoreVoucherRequest $req) {
        try {
            $data = $req->only([
                'code',
                'quantity',
                'discount',
                'start_date',
                'end_date',
            ]);

            $voucher = $this->voucherService->createVoucherSystem($data);

            return new VoucherResource($voucher);
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) { 
                return response()->json(['error' => "Code đã tồn tại"], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
