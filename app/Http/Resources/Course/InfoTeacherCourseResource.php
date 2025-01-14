<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InfoTeacherCourseResource extends JsonResource
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
            'name' => $this->name ?? null,
            'teacher' => [
                'id' => $this->teacher->id ?? null,
                'fullname' => $this->teacher->user->fullname ?? null,
                'email' => $this->teacher->user->email ?? null,
                'avatar' => $this->teacher->user->avatar ? url('images/users/'.  $this->teacher->user->avatar) : null,
                'bio' => $this->teacher->bio ?? null,
                "job_title" => $this->teacher->job_title ?? null,
                "facebook" => $this->teacher->facebook ?? null,
                "twitter" => $this->teacher->twitter ?? null,
                "linkedin" => $this->teacher->linkedin ?? null,
                "youtube" => $this->teacher->youtube ?? null,
                'total_course' => $this->teacher->courses->count() ?? null,
                'rating' => $this->teacher->rating ?? null,
                'count_review' => $this->teacher->getTotalReviewCount()
            ],
        ];
    }
}
