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

    public function createChapter(array $data) {
        $user = JWTAuth::parseToken()->authenticate();
        
        if(!$user->teacher()->exists()) {
            throw new NotTeacherException();
        }

        // Kiểm tra nếu khóa học không thuộc về giáo viên
        $course = $this->courseRepo->find($data['course_id']);
        if (!$course || $course->teacher_id !== $user->teacher->id) {
            throw new \Exception("Bạn không có quyền tạo chương cho khóa học này.");
        }

        return $this->chapterRepo->create($data);
    }
}
