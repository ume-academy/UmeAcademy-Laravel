<?php

namespace App\Http\Resources\Voucher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
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
            'code ' => $this->code ?? null,
            'quantity' => $this->quantity ?? null,
            'discount' => $this->discount ?? null,
            'start_date' => $this->start_date ?? null,
            'end_date' => $this->end_date ?? null,
            'course' => [
                'id' => $this->course->id,
                'name' => $this->course->name,
            ],
        ];
    }
}
