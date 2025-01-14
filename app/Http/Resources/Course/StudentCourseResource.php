<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'fullname' => $this->fullname ?? null,  
            'avatar' => $this->avatar ? url('/images/users/'. $this->avatar) : null,   
            'email' => $this->email ?? null,     
            'registered_at' => $this->pivot->created_at ?? null, 
            'progress' => $this->progress ?? null
        ];
    }
}
