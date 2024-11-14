<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Voucher\StoreTeacherVoucherRequest;
use App\Http\Resources\Voucher\TeacherVoucherResource;
use App\Services\TeacherVoucherService;
use Illuminate\Database\QueryException;

class VoucherController extends Controller
{
    public function __construct(
        private TeacherVoucherService $teacherVoucherService,
    ){}

    public function createVoucher(StoreTeacherVoucherRequest $req, $id) {
        try {
            $data = $req->only([
                'code',
                'quantity',
                'discount',
                'start_date',
                'end_date',
            ]);
            $data['course_id'] = $id;

            $voucher = $this->teacherVoucherService->createVoucher($data);

            return new TeacherVoucherResource($voucher);
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) { 
                return response()->json(['error' => "Code đã tồn tại"], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAllVoucher($id) {
        try {
            $vouchers = $this->teacherVoucherService->getAllVoucher($id);

            return TeacherVoucherResource::collection($vouchers);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
