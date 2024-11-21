<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
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
            'name' => $this->user->fullname ?? null,
            'email' => $this->user->email ?? null,
            'bio' => $this->bio ?? null,
            "rating" => $this->rating ?? 5,
            "job_title" => $this->job_title ?? null,
            "facebook" => $this->facebook ?? null,
            "twitter" => $this->twitter ?? null,
            "linkedin" => $this->linkedin ?? null,
            "youtube" => $this->youtube ?? null,
            "created_at" => $this->created_at ?? null
        ];
    }
}
