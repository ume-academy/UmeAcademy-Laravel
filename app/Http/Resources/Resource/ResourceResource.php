<?php

namespace App\Http\Resources\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceResource extends JsonResource
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
            'file' => $this->name ? url('resources/courses/'. $this->name) : null,
            'lesson' => [
                'id' => $this->lesson->id,
                'name' => $this->lesson->name
            ]
        ];
    }
}
