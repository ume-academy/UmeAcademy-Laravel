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
            'fullname' => $this->user->fullname ?? null,
            'email' => $this->user->email ?? null,
            'avatar' => $this->user->avatar ? url('images/users/'. $this->user->avatar) : null,
            'total_course' => $this->courses()->count(),
            'count_review' => $this->getTotalReviewCount() ?? null,
            'bio' => $this->bio ? $this->bio : $this->user->bio,
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
