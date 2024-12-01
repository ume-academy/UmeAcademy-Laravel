<?php

namespace App\Http\Resources\WithdrawMethod;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawRequestResource extends JsonResource
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
            'status' => $this->status ?? null,
            'teacher' => [
                'id' => $this->teacher->id ?? null,
                'name' => $this->teacher->user->fullname ?? null
            ],
            'created_at' => $this->created_at ?? null
        ];
    }
}
