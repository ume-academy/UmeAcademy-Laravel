<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'summary' => $this->summary ?? null,
            'thumbnail' => $this->thumbnail ? url('images/courses/'. $this->thumbnail) : null,
            'description' => $this->description ?? null,
            'video' => $this->video ? url('videos/courses/'. $this->video) : null,
            'price' => $this->price ?? null,
            'duration' => $this->duration ?? null,
            'total_lesson' => $this->total_lesson ?? null,
            'total_chapter' => $this->total_chapter ?? null,
            'total_student' => $this->total_student ?? 0,
            'total_review' => $this->reviews->count() ?? null,
            'rating' => $this->rating ?? null,
            'status' => $this->status ?? null,
            'category' => [
                'id' => $this->category->id ?? null,
                'name' => $this->category->name ?? null
            ],
            'level' => [
                'id' => $this->level->id ?? null,
                'name' => $this->level->name ?? null
            ],
            'teacher' => [
                'id' => $this->teacher->id ?? null,
                'fullname' => $this->teacher->user->fullname ?? null,
                'avatar' => $this->teacher->user->avatar ? url('images/users/'. $this->teacher->user->avatar) : null
            ],
            'badges' => [
                'badge' => $this->badge ?? null,
                'category' => $this->category->name ?? null 
            ]
        ];
    }
}
