<?php

namespace App\Http\Resources\Article;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            'title' => $this->title ?? null,
            'thumbnail' => $this->thumbnail ? url('images/articles/'. $this->thumbnail) : null,
            'user' => [
                'fullname' => $this->user->fullname ?? null,
                'email' => $this->user->email ?? null,
            ],
            'created_at' => $this->created_at ?? null,
        ];
    }
}
