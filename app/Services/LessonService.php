<?php

namespace App\Services;

use App\Repositories\Interfaces\ChapterRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\LessonRepositoryInterface;
use App\Traits\ValidationTrait;
use Tymon\JWTAuth\Facades\JWTAuth;

class LessonService
{
    use ValidationTrait;

    public function __construct(
        private LessonRepositoryInterface $lessonRepo,
        private CourseRepositoryInterface $courseRepo,
        private ChapterRepositoryInterface $chapterRepo
    ){}

    public function createLesson(array $data)
    {
        $teacher = $this->validateTeacher();

        // Kiểm tra khóa học và chapter
        $course = $this->validateCourse($teacher, $data['course_id']);
        $this->validateChapter($course, $data['chapter_id']);

        return $this->lessonRepo->create($data);
    }

    public function markLessonCompleted(array $data) {
        $user = JWTAuth::parseToken()->authenticate();

        $course = $this->courseRepo->getById($data['course_id']);
        $chapter = $this->validateChapter($course, $data['chapter_id']);
        $lesson = $this->validateLesson($chapter, $data['lesson_id']);

        // Kiểm tra xem người dùng đã đk khóa học chưa
        if($course->checkEnrolled($user->id)) {
            return $this->lessonRepo->syncLessonCompleted($lesson, [$user->id]);
        } else {
            throw new \Exception('Bạn chưa mua khóa học'); 
        }
        
    }

    public function updateLesson($lessonId, $data) {
        $teacher = $this->validateTeacher();

        // Kiểm tra khóa học và chapter
        $course = $this->validateCourse($teacher, $data['course_id']);
        $chapter = $this->validateChapter($course, $data['chapter_id']);
        $this->validateLesson($chapter, $lessonId);

        $data = ['name' => $data['name']];

        return $this->lessonRepo->update($lessonId, $data);
    }
}
