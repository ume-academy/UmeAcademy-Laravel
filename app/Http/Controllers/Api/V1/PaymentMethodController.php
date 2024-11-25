<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentMethod\StorePaymentMethodRequest;
use App\Http\Requests\PaymentMethod\UpdatePaymentMethodRequest;
use App\Http\Resources\PaymentMethod\PaymentMethodResource;
use App\Services\PaymentMethodService;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function __construct(
        private PaymentMethodService $paymentMethodService
    ){}

    public function getAllPaymentMethod() {
        try {
            $data = $this->paymentMethodService->getAllPaymentMethod();
            return PaymentMethodResource::collection($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createPaymentMethod(StorePaymentMethodRequest $req) {
        try {
            $data = $req->only(['name']);

            $method = $this->paymentMethodService->createPaymentMethod($data);
            return new PaymentMethodResource($method);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updatePaymentMethod(UpdatePaymentMethodRequest $req, $id) {
        try {
            $data = $req->only(['name']);

            $method = $this->paymentMethodService->updatePaymentMethod($id, $data);
            
            if($method) {
                return response()->json(['message' => 'Cập nhật thành công']);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deletePaymentMethod($id) {
        try {
            $method = $this->paymentMethodService->deletePaymentMethod($id);
            
            if($method) {
                return response()->json(['message' => 'Xóa thành công']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function detailPaymentMethod($id) {
        try {
            $method = $this->paymentMethodService->detailPaymentMethod($id);
            
            return new PaymentMethodResource($method);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
