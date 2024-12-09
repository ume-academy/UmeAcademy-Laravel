<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Role;

class UserResource extends JsonResource
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
            "id_admin" => (bool) $this->hasAnyRole(Role::all()->pluck('name')->toArray()),
            "bio"=> $this->bio,
            "is_lock"=> $this->is_lock,
            "email_verified_at"=> $this->email_verified_at,
            'created_at' => $this->created_at,
        ];
    }
}
