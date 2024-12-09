<?php

namespace App\Services;

use App\Repositories\Interfaces\ChapterRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\LessonRepositoryInterface;
use App\Services\Email\EmailService;
use App\Traits\ValidationTrait;
use Tymon\JWTAuth\Facades\JWTAuth;

class LessonService
{
    use ValidationTrait;
    private $emailSender;

    public function __construct(
        private LessonRepositoryInterface $lessonRepo,
        private CourseRepositoryInterface $courseRepo,
        private ChapterRepositoryInterface $chapterRepo,
        private PDFService $pdfService,
    ){
        $this->emailSender = new EmailService(); 
    }

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
            $result = $this->lessonRepo->syncLessonCompleted($lesson, [$user->id]);

            $totalLessons = $course->total_lesson;

            $progress = $totalLessons > 0
                ? $this->courseRepo->completedLessons($course->id, $user->id)->count() / $totalLessons * 100
                : 0;

            if ($progress == 100) {
                $fileName = $this->pdfService->createCertificate($course, $user);
                
                // Gửi email
                $this->emailSender->sendCertificate($user, $fileName);
            }

            return $result;
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

        if($course->status == 2) {
            throw new \Exception('Không thể cập nhật khóa học vì khóa học đã được phê duyệt.');
        }

        $data = ['name' => $data['name']];

        return $this->lessonRepo->update($lessonId, $data);
    }
}
