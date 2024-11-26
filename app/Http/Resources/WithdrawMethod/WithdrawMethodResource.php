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
            'id' => $this->id,
            'name_bank' => $this->name_bank,
            'name_account' => $this->name_account,
            'branch' => $this->branch,
            'min_withdraw' => $this->min_withdraw,
            'teacher_id' => $this->teacher_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
