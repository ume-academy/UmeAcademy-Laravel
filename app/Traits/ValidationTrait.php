<?php

namespace App\Traits;

use App\Exceptions\Teacher\NotTeacherException;
use Tymon\JWTAuth\Facades\JWTAuth;

trait ValidationTrait 
{
    public function validateTeacher()
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user->teacher()->exists()) {
            throw new NotTeacherException();
        }

        return $user->teacher;
    }

    public function validateCourse($teacher, $courseId)
    {
        $course = $this->courseRepo->find($courseId);
        if (!$course || $course->teacher_id !== $teacher->id) {
            throw new \Exception("Bạn không có quyền cho khóa học này.");
        }

        return $course;
    }

    public function validateChapter($course, $chapterId)
    {
        $chapter = $this->chapterRepo->find($chapterId);
        if (!$chapter || $chapter->course_id !== $course->id) {
            throw new \Exception("Chapter này không thuộc về khóa học mà bạn đang thao tác.");
        }

        return $chapter;
    }

    public function validateLesson($chapter, $lessonId)
    {
        $lesson = $this->lessonRepo->find($lessonId);
        if (!$lesson || $lesson->chapter_id !== $chapter->id) {
            throw new \Exception("Lesson này không thuộc về chapter mà bạn đang thao tác.");
        }

        return $lesson;
    }
}
