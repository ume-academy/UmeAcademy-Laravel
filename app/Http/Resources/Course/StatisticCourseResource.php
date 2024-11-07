<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatisticCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'total_lesson' => $this->total_lesson ?? null,
            'total_chapter' => $this->total_chapter ?? null,
            'total_duration' => $this->duration ?? null,
            'total_student' => $this->total_student ?? 0,
            'level' => [
                'id' => $this->level->id ?? null,
                'name' => $this->level->name ?? null
            ],
        ];
    }
}
