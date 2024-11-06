<?php

namespace App\Services\Lesson;

use App\Contracts\CreateLessonInterface;
use App\Repositories\Interfaces\ChapterRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\LessonRepositoryInterface;
use App\Traits\ValidationTrait;

class CreateLessonService implements CreateLessonInterface
{
    use ValidationTrait;

    public function __construct(
        private LessonRepositoryInterface $lessonRepo,
        private CourseRepositoryInterface $courseRepo,
        private ChapterRepositoryInterface $chapterRepo
    ){}

    public function createLesson(array $data)
    {
        $user = $this->validateTeacher();

        // Kiểm tra khóa học và chapter
        $course = $this->validateCourse($user, $data['course_id']);
        $this->validateChapter($course, $data['chapter_id']);

        return $this->lessonRepo->create($data);
    }
}
