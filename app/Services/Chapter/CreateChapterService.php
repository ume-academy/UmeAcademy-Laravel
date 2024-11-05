<?php

namespace App\Services\Chapter;

use App\Contracts\CreateChapterServiceInterface;
use App\Exceptions\Teacher\NotTeacherException;
use App\Repositories\Interfaces\ChapterRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateChapterService implements CreateChapterServiceInterface
{
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

    private function validateTeacher()
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user->teacher()->exists()) {
            throw new NotTeacherException();
        }

        return $user;
    }

    // Kiểm tra nếu khóa học không tồn tại hoặc không thuộc về giáo viên
    private function validateCourse($user, $courseId)
    {
        $course = $this->courseRepo->find($courseId);

        if (!$course || $course->teacher_id !== $user->teacher->id) {
            throw new \Exception("Bạn không có quyền tạo chương cho khóa học này.");
        }
    }

}
