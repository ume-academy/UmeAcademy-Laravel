<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\CheckoutRequest;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    ){}

    public function checkout(CheckoutRequest $req) {
        try {
            $data = $req->only([
                'origin_price',
                'course_id',
                'payment_method_id',
                'voucher_id'
            ]);

            return $this->paymentService->checkout($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function confirmWebhook(Request $req) {
        try {
            $data = $req->all();

            return $this->paymentService->confirmWebhook($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function cancel(Request $req) {
        try {
            $data = $req->all();

            $this->paymentService->cancel($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return redirect()->to('https://umeacademy.online/course-payment-method');
    }
}
