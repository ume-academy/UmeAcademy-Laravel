<?php

namespace App\Http\Resources\Wallet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->code ?? null,
            'type' => $this->type ?? null,
            // 'type' => 'Chuyển khoản ngân hàng',
            'balance_tracking' => $this->balance_tracking ?? null,
            'note' => $this->note ?? null,
            'created_at' => $this->created_at ?? null
        ];
    }
}
