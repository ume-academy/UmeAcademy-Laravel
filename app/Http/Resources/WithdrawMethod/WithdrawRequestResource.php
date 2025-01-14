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
            'code' => $this->code ?? null,
            'money' => $this->money ?? null,
            'status' => $this->status ?? null,
            'teacher' => [
                'id' => $this->teacher->id ?? null,
                'name' => $this->teacher->user->fullname ?? null,
                'bank' => [
                    "id" => $this->teacher->withdrawMethod->id ?? null,
                    "name_bank" => $this->teacher->withdrawMethod->name_bank ?? null,
                    "name_account" => $this->teacher->withdrawMethod->name_account ?? null,
                    "number_account" => $this->teacher->withdrawMethod->number_account ?? null,
                    "min_withdraw" => $this->teacher->withdrawMethod->min_withdraw ?? null,
                ],
            ],
            'created_at' => $this->created_at ?? null
        ];
    }
}
