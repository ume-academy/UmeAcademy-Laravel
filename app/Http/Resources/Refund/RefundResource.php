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
            'money' => $this->money ?? null,
            'refund_reason' => $this->refund_reason ?? null,
            'student' => $this->user->fullname ?? null,
            'course' => $this->course->name ?? null,
            'teacher' => $this->teacher->user->fullname ?? null,
            'status' => $this->status ?? null,
            'created_at' => $this->created_at ?? null,
        ];
    }
}
