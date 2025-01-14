<?php

namespace App\Http\Resources\Refund;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RefundResource extends JsonResource
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
            'refund_reason' => $this->refund_reason ?? null,
            'price' => $this->transaction->discount_price ?? null,
            'student' => $this->transaction->user->fullname ?? null,
            'course' => $this->transaction->course->name ?? null,
            'teacher' => $this->transaction->course?->teacher?->user?->fullname ?? null,
            'price' => $this->transaction->discount_price ?? null,
            'status' => $this->status ?? null,
            'created_at' => $this->created_at ?? null,
        ];
    }
}
