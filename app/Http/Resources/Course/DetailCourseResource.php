<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailCourseResource extends JsonResource
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
            'course_requirement' => $this->course_requirement,
            'course_learning_benefit' => $this->course_learning_benefit,
            'content' => new ContentCourseDetailResource($this),
            'voucher' => $this->vouchers,
            'status' => $this->status,
            'category' => [
                'id' => $this->category->id ?? null,
                'name' => $this->category->name ?? null
            ],
            'level' => [
                'id' => $this->level->id ?? null,
                'name' => $this->level->name ?? null
            ],
            'created_at' => $this->created_at ?? null
        ];
    }
}
