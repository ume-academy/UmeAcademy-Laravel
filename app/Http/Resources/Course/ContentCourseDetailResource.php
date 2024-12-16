<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentCourseDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'total_chapter' => $this->total_chapter ?? 0,
            'total_lesson' => $this->total_lesson ?? 0,
            'total_duration' => $this->duration ?? 0,
            'chapters' => $this->formatChapters(),
        ];
    }

    private function formatChapters(): array
    {
        return $this->chapters->map(function ($chapter) {
            return [
                'id' => $chapter->id,
                'name' => $chapter->name,
                'total_lesson' => $chapter->lessons->count(),
                'chapter_duration' => $this->chapterDuration($chapter),
                'lessons' => $this->formatLessons($chapter->lessons),
            ];
        })->toArray();
    }

    private function formatLessons($lessons): array
    {
        return $lessons->map(function ($lesson) {
            return [
                'id' => $lesson->id,
                'name' => $lesson->name,
                'is_preview' => $lesson->video?->is_preview ?? false,
                'video_link' => isset($lesson->video) ? url('videos/courses/' . $lesson->video->name) : null,
                'video_duration' => $lesson->video ? $lesson->video->duration : 0,
                'resources' => $lesson->resources->map(function($resource) {
                    return [
                        'id' => $resource['id'] ?? null,
                        'name' => $resource['name'] ?? null,
                        'created_at' => $resource['created_at'] ?? null,
                    ];
                })
            ];
        })->toArray();
    }

    private function chapterDuration($chapter): int
    {
        return $chapter->lessons->sum(function ($lesson) {
            return $lesson->video ? $lesson->video->duration : 0;
        });
    }
}
