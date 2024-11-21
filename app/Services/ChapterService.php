<?php

namespace App\Services;

use App\Repositories\Interfaces\ChapterRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Traits\ValidationTrait;

class ChapterService
{
    use ValidationTrait;

    public function __construct(
        private ChapterRepositoryInterface $chapterRepo,
        private CourseRepositoryInterface $courseRepo
    ){}

    public function createChapter(array $data)
    {
        $teacher = $this->validateTeacher();

        // Kiểm tra quyền sở hữu của khóa học
        $this->validateCourse($teacher, $data['course_id']);

        return $this->chapterRepo->create($data);
    }

    public function updateChapter($chapterId, $data) {
        $teacher = $this->validateTeacher();
        
        // Kiểm tra quyền sở hữu của khóa học
        $course = $this->validateCourse($teacher, $data['course_id']);
        $this->validateChapter($course, $chapterId);

        $data = ['name' => $data['name']];
        
        return $this->chapterRepo->update($chapterId, $data);
    }
}
