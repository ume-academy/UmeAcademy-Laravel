<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fee\UpdateFeeRequest;
use App\Services\FeeService;

class FeeController extends Controller
{
    public function __construct(
        private FeeService $feeService
    ){}

    public function update(UpdateFeeRequest $req, $id) {
        try {
            $data = $req->only(['fee']);
            
            $this->feeService->update($id, $data);
            return response()->json([
                'message' => 'Cập nhật hoa hồng thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function get($id) {
        try {
            $fee = $this->feeService->get($id);
            return response()->json([
                'fee' => $fee->fee
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
