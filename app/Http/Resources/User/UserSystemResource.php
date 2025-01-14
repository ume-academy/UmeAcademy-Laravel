<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSystemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fullname' => $this->fullname,
            'email' => $this->email,
            'email_verified' => (bool) $this->email_verified_at,
            "avatar" => $this->avatar ? url('/images/users/' . $this->avatar) : null,
            "is_teacher" => (bool) $this->isTeacher,
            "bio"=> $this->bio,
            "role" => $this->roles,
            "is_lock"=> $this->is_lock,
            "email_verified_at"=> $this->email_verified_at,
            'created_at' => $this->created_at,
        ];
    }
}
