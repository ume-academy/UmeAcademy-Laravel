<?php

namespace App\Services\Lesson;

use App\Contracts\CreateLessonServiceInterface;
use App\Exceptions\Teacher\NotTeacherException;
use App\Repositories\Interfaces\ChapterRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\LessonRepositoryInterface;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateLessonService implements CreateLessonServiceInterface
{
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
            throw new \Exception("Bạn không có quyền tạo bài học cho khóa học này.");
        }

        return $course;
    }

    // Kiểm tra nếu chapter không thuộc về khóa học
    private function validateChapter($course, $chapterId)
    {
        $chapter = $this->chapterRepo->find($chapterId);
        if (!$chapter || $chapter->course_id !== $course->id) {
            throw new \Exception("Chapter này không thuộc về khóa học mà bạn đang thao tác.");
        }

        return $chapter;
    }




}
