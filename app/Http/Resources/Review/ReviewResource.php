<?php

namespace App\Http\Resources\Review;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'content' => $this->content ?? null,
            'rating' => $this->rating ?? null,
            'user' => [
                'id' => $this->user->id ?? null,
                'fullname' => $this->user->fullname ?? null,
                'avatar' => $this->user->avatar ? url('images/users/'. $this->user->avatar) : null, 
            ],
            'created_at' => $this->created_at ?? null,
        ];
    }
}
