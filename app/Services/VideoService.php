<?php

namespace App\Services;

use App\Repositories\Interfaces\ChapterRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\LessonRepositoryInterface;
use App\Repositories\Interfaces\VideoRepositoryInterface;
use App\Traits\HandleFileTrait;
use App\Traits\ValidationTrait;
use FFMpeg\FFMpeg;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class VideoService
{
    use HandleFileTrait, ValidationTrait;

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
            $teacher = $this->validateTeacher();

            $course = $this->validateCourse($teacher, $data['course_id']);
            $chapter = $this->validateChapter($course, $data['chapter_id']);
            $lesson = $this->validateLesson($chapter, $data['lesson_id']);

            if($course->status == 2) {
                throw new \Exception('Không thể tạo vì khóa học đã được phê duyệt.');
            }

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
                HandleFileTrait::uploadFile($data['video'], $videoData['name'], 'courses', 'videos');
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
                throw new \Exception('Video đã tồn tại trong bài học này.');
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

    public function updateVideo(array $data)
    {

        $teacher = $this->validateTeacher();

        $course = $this->validateCourse($teacher, $data['course_id']);
        $chapter = $this->validateChapter($course, $data['chapter_id']);
        $lesson = $this->validateLesson($chapter, $data['lesson_id']);
        $video = $lesson->video;

        if($course->status == 2) {
            throw new \Exception('Không thể chỉnh sửa vì khóa học đã được phê duyệt.');
        }
        
        return $video = $this->videoRepo->updateVideo($video->id, $data['is_preview']);
    }

    public function deleteVideo($data) {
        $teacher = $this->validateTeacher();

        $course = $this->validateCourse($teacher, $data['course_id']);
        $chapter = $this->validateChapter($course, $data['chapter_id']);
        $lesson = $this->validateLesson($chapter, $data['lesson_id']);
        $video = $lesson->video;

        if($course->status == 2) {
            throw new \Exception('Không thể xóa vì khóa học đã được phê duyệt.');
        }
        
        return $video = $this->videoRepo->deleteVideo($video->id);
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
