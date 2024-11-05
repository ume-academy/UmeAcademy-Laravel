<?php

namespace App\Http\Resources\Chapter;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChapterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name ?? null,
            'course' => [
                'id' => $this->course->id ?? null,
                'name' => $this->course->name ?? null
            ]
        ];
    }
}
