<?php

namespace App\Http\Resources\WithdrawMethod;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawMethodResource extends JsonResource
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
            'name_bank' => $this->name_bank ?? null,
            'name_account' => $this->name_account ?? null,
            'branch' => $this->branch ?? null,
            'min_withdraw' => $this->min_withdraw ?? null,
            'teacher' => [
                'id' => $this->teacher->id ?? null,
                'name' => $this->teacher?->user->fullname ?? null,
            ],
            'created_at' => $this->created_at,
        ];
    }
}
