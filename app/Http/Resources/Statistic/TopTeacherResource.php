<?php

namespace App\Http\Resources\Statistic;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TopTeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->teacher->id ?? null,
            'fullname' => $this->teacher?->user->fullname ?? null,
            'email' => $this->teacher?->user->email ?? null,
            'avatar' => $this->teacher?->user->avatar ? url('/images/users/'. $this->teacher->user->avatar) : null,
            'total_earnings' => $this->total_earnings ?? null,
        ];
    }
}
