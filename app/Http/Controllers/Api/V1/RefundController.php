<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Refund\RefundResource;
use App\Services\RefundService;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function __construct(
        private RefundService $refundService
    ){}

    public function getAllRefundRequest(Request $req) {
        try {
            $perPage = $req->input('per_page', 10);

            $transactions = $this->refundService->getAllRefundRequest($perPage);
            return RefundResource::collection($transactions);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $req, $id) {
        try {
            $status = $req->input('status');
            
            $request = $this->refundService->updateStatus($id, $status);
            return new RefundResource($request);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
