<?php

namespace App\Services;

use App\Repositories\Interfaces\ChapterRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\LessonRepositoryInterface;
use App\Repositories\Interfaces\ResourceRepositoryInterface;
use App\Repositories\Interfaces\VideoRepositoryInterface;
use App\Services\Email\EmailService;
use App\Traits\ValidationTrait;
use Illuminate\Support\Facades\DB;
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
        private VideoRepositoryInterface $videoRepo,
        private ResourceRepositoryInterface $resourceRepo,
    ){
        $this->emailSender = new EmailService(); 
    }

    public function createLesson(array $data)
    {
        $teacher = $this->validateTeacher();

        // Kiểm tra khóa học và chapter
        $course = $this->validateCourse($teacher, $data['course_id']);
        $this->validateChapter($course, $data['chapter_id']);

        if($course->status == 2) {
            throw new \Exception('Không thể thêm mới bài học vì khóa học đã được phê duyệt.');
        }

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

    public function deleteLesson($lessonId, $data) {
        $teacher = $this->validateTeacher();

        // Kiểm tra khóa học và chapter
        $course = $this->validateCourse($teacher, $data['course_id']);
        $chapter = $this->validateChapter($course, $data['chapter_id']);
        $lesson = $this->validateLesson($chapter, $lessonId);
        $video = $lesson->video;
        $resources = $lesson->resources;

        if($course->status == 2) {
            throw new \Exception('Không thể xóa bài học vì khóa học đã được phê duyệt.');
        }

        DB::beginTransaction();
        try {
            // Xóa bài học
            $this->lessonRepo->delete($lesson->id);

            // Xóa video nếu tồn tại
            if ($video) {
                $this->videoRepo->deleteVideo($video->id);
            }

            if ($resources->isNotEmpty()) {
                foreach ($resources as $resource) {
                    $this->resourceRepo->delete($resource->id);
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Xóa thất bại'], 500);
        }
    }
}
