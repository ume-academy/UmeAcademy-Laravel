<?php

namespace App\Services;

use App\Repositories\Interfaces\ChapterRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\LessonRepositoryInterface;
use App\Repositories\Interfaces\ResourceRepositoryInterface;
use App\Traits\HandleFileTrait;
use App\Traits\ValidationTrait;
use Illuminate\Support\Facades\DB;

class ResourceService
{
    use HandleFileTrait, ValidationTrait;

    public function __construct(
        private ResourceRepositoryInterface $resourceRepo,
        private CourseRepositoryInterface $courseRepo,
        private ChapterRepositoryInterface $chapterRepo,
        private LessonRepositoryInterface $lessonRepo
    ){}

    public function createResource(array $data)
    {
        DB::beginTransaction();

        try {
            $teacher = $this->validateTeacher();

            $course = $this->validateCourse($teacher, $data['course_id']);
            $chapter = $this->validateChapter($course, $data['chapter_id']);
            $lesson = $this->validateLesson($chapter, $data['lesson_id']);

            $resourceData = [
                'name' => HandleFileTrait::generateName($data['resource']),
                'lesson_id' => $lesson->id,
            ];

            // Thêm vào db
            $resource = $this->resourceRepo->create($resourceData);

            if ($resource) {
                HandleFileTrait::uploadFile($data['resource'], $resourceData['name'], 'courses', 'resources');
            }

            DB::commit();
            return $resource;

        } catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception('Lỗi khi tạo tài nguyên: ' . $e->getMessage());
        }
    }
}
