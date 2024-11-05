<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OverviewCourseResource extends JsonResource
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
                'fullname' => $this->teacher->user->fullname ?? null,
                'email' => $this->teacher->user->email ?? null
            ],
        ];
    }
}
