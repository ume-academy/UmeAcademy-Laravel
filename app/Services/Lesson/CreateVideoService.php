<?php

namespace App\Services\Lesson;

use App\Contracts\CreateVideoServiceInterface;
use App\Exceptions\Teacher\NotTeacherException;
use App\Repositories\Interfaces\ChapterRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\LessonRepositoryInterface;
use App\Repositories\Interfaces\VideoRepositoryInterface;
use App\Traits\HandleFileTrait;
use FFMpeg\FFMpeg;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateVideoService implements CreateVideoServiceInterface
{
    use HandleFileTrait;

    public function __construct(
        private VideoRepositoryInterface $videoRepo,
        private CourseRepositoryInterface $courseRepo,
        private ChapterRepositoryInterface $chapterRepo,
        private LessonRepositoryInterface $lessonRepo
    ){}

    public function createVideo(array $data)
    {
        DB::beginTransaction();

        try {
            $user = $this->validateTeacher();

            $course = $this->validateCourse($user, $data['course_id']);
            $chapter = $this->validateChapter($course, $data['chapter_id']);
            $lesson = $this->validateLesson($chapter, $data['lesson_id']);

            // Lấy đường dẫn tạm thời
            $path = $this->getPathVideo($data['video']);

            // Lấy duration video
            $duration = $this->getVideoDuration($path);

            $videoData = [
                'name' => HandleFileTrait::generateName($data['video']),
                'duration' => $duration,
                'lesson_id' => $lesson->id,
            ];

            // Thêm vào db
            $video = $this->videoRepo->create($videoData);

            if ($video) {
                HandleFileTrait::uploadFile($data['video'], $videoData['name'], 'courses', true);
            }

            // Xóa file tạm sau khi xử lý
            if (file_exists($path)) {
                unlink($path);
            }

            DB::commit();
            return $video;

        } catch (QueryException $e) {
            DB::rollBack();
    
            if (isset($path) && file_exists($path)) {
                unlink($path);
            }
    
            // Kiểm tra mã lỗi để xử lý riêng lỗi unique
            if ($e->errorInfo[1] == 1062) { 
                throw new \Exception('Video đã tồn tại cho bài học này.');
            }
    
            throw new \Exception('Lỗi khi tạo video: ' . $e->getMessage());
    
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($path) && file_exists($path)) {
                unlink($path);
            }
            throw new \Exception('Lỗi khi tạo video: ' . $e->getMessage());
        }
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

    // Kiểm tra lesson có thuộc về chapter không
    private function validateLesson($chapter, $lessonId)
    {
        $lesson = $this->lessonRepo->find($lessonId);

        if (!$lesson || $lesson->chapter_id !== $chapter->id) {
            throw new \Exception("Lesson này không thuộc về chapter mà bạn đang thao tác.");
        }

        return $lesson;
    }

    // Lưu video tạm thời
    private function getPathVideo($file)
    {
        $path = $file->store('videos/tmp');

        return storage_path('app/' . $path);
    }

    // Lấy thời lượng video
    private function getVideoDuration($filePath)
    {
        $ffmpeg = FFMpeg::create();
        $video = $ffmpeg->open($filePath);

        $durationInSeconds = $video->getFormat()->get('duration');

        return $durationInSeconds;
    }
}
