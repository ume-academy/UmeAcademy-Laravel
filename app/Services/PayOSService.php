<?php

namespace App\Services;

use App\Repositories\Interfaces\CourseRepositoryInterface;
use PayOS\PayOS;

class PayOSService
{
    private $payOSClientId;
    private $payOSApiKey;
    private $payOSChecksumKey;

    public function __construct(
        private CourseRepositoryInterface $courseRepo,
    ){
        $this->payOSClientId = env('PAYOS_CLIENT_ID');
        $this->payOSApiKey = env('PAYOS_API_KEY');
        $this->payOSChecksumKey = env('PAYOS_CHECKSUM_KEY');
    }

    public function checkout(array $data) {
        $course = $this->courseRepo->getById($data['course_id']);

        // Khởi tạo PayOS
        $payOS = new PayOS($this->payOSClientId, $this->payOSApiKey, $this->payOSChecksumKey);

        $data = [
            "orderCode" => intval(substr(strval(microtime(true) * 10000), -6)),
            "amount" => intval($data['discount_price']),
            "description" => "Thanh toán đơn hàng",
            "items" => [
                0 => [
                    'name' => $course->name,
                    'price' => intval($course->price),
                    'quantity' => 1
                ]
            ],
            "returnUrl" => env('APP_URL') . "/course/$course->id",
            "cancelUrl" => "http://127.0.0.1:8000/api/v1/cancel"
        ];

        $response = $payOS->createPaymentLink($data);
        
        return $response;
    }
}
