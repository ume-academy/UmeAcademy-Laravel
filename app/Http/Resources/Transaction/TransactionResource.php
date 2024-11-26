<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,
            'transaction_code' => $this->transaction_code ?? null,
            'origin_price' => $this->origin_price ?? null,
            'discount_price' => $this->discount_price ?? null,
            'user' => [
                'id' => $this->user->id ?? null,
                'name' => $this->user->fullname ?? null,
            ],
            'course' => [
                'id' => $this->course->id ?? null,
                'name' => $this->course->name ?? null,
            ],
            'payment_method' => [
                'id' => $this->paymentMethod->id ?? null,
                'name' => $this->paymentMethod->name ?? null,
            ],
            'created_at' => $this->created_at ?? null
        ];
    }
}
