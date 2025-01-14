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
            'description' => $this->description ?? null,
            'course_requirement' => $this->course_requirement,
            'course_learning_benefit' => $this->course_learning_benefit,
        ];
    }
}
