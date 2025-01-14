<?php

namespace App\Http\Resources\Lesson;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
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
            'positions' => $this->positions ?? null,
            'chapter' => [
                'id' => $this->chapter->id ?? null,
                'name' => $this->chapter->name ?? null
            ]
        ];
    }
}
