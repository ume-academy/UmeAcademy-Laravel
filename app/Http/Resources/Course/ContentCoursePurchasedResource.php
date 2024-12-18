<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentCoursePurchasedResource extends JsonResource
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
            'thumbnail' => $this->thumbnail ? url('images/courses/'. $this->thumbnail) : null,
            'total_chapter' => $this->total_chapter ?? 0,
            'total_lesson' => $this->total_lesson ?? 0,
            'total_duration' => $this->duration ?? 0,
            'total_lesson_completed' => $this->completed_lesson ?? 0,
            'progress' => $this->total_lesson > 0 ? ($this->completed_lesson / $this->total_lesson * 100) : 0,
            'chapters' => $this->formatChapters(),
        ];
    }

    private function formatChapters(): array
    {
        return $this->chapters->map(function ($chapter, $index) {
            // Lấy số lượng bài học đã hoàn thành từ mảng lesson_in_chapter
            $completedLessonsInChapter = $this->completed_lesson_in_chapter[$index] ?? 0;

            return [
                'id' => $chapter->id,
                'name' => $chapter->name,
                'total_lesson' => $chapter->lessons->count(),
                'chapter_duration' => $this->chapterDuration($chapter),
                'lesson_completed' => $completedLessonsInChapter, 
                'lessons' => $this->formatLessons($chapter->lessons),
            ];
        })->toArray();
    }


    private function formatLessons($lessons): array
    {
        $completedLessonIds = $this->lesson_completed_ids ?? [];

        return $lessons->map(function ($lesson) use ($completedLessonIds) {
            return [
                'id' => $lesson->id,
                'name' => $lesson->name,
                'video_link' => $lesson->video ? url('videos/courses/' . $lesson->video->name) : null,
                'video_duration' => $lesson->video ? $lesson->video->duration : 0,
                'is_completed' => in_array($lesson->id, $completedLessonIds),
                'resources' => $lesson->resources->map(function($resource) {
                    return [
                        'id' => $resource['id'] ?? null,
                        'name' => url('resources/courses/'. $resource['name']) ?? null,
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
