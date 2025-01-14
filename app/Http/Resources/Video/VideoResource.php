<?php

namespace App\Http\Resources\Video;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
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
            'video' => $this->name ? url('videos/courses/'. $this->name) : null,
            'duration' => $this->duration ?? null,
            'is_preview' => $this->is_preview ?? null,
            'lesson' => [
                'id' => $this->lesson->id,
                'name' => $this->lesson->name
            ]
        ];
    }
}
