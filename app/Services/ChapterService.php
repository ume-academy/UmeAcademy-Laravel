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
        $user = $this->validateTeacher();

        // Kiểm tra quyền sở hữu của khóa học
        $this->validateCourse($user, $data['course_id']);

        return $this->chapterRepo->create($data);
    }
}
