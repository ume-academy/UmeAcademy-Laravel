<?php

namespace App\Services\Course;

use App\Contracts\CreateCourseServiceInterface;
use App\Exceptions\Teacher\NotTeacherException;
use App\Repositories\Interfaces\ChapterRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Traits\HandleFileTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateCourseService implements CreateCourseServiceInterface
{
    use HandleFileTrait;

    public function __construct(
        private CourseRepositoryInterface $courseRepo,
        private ChapterRepositoryInterface $chapterRepo,
    ){}

    public function createCourse(array $data)
    {
        DB::beginTransaction();  

        try {
            $user = $this->validateTeacher();

            // Xử lý ảnh thumbnail
            $data['thumbnail'] = $this->handleThumbnail($data['thumbnail']);
            $data['teacher_id'] = $user->teacher->id;

            // Tạo khóa học
            $course = $this->courseRepo->create($data);

            if ($course) {
                // Tạo chương giới thiệu cho khóa học
                $this->createIntroductionChapter($course->id);
            }

            DB::commit(); 
            return $course;

        } catch (Exception $e) {
            DB::rollBack(); 
            throw new Exception('Lỗi khi tạo khóa học: ' . $e->getMessage());
        }
    }

    // Kiểm tra người dùng có phải là giáo viên không
    private function validateTeacher()
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user->teacher()->exists()) {
            throw new NotTeacherException();
        }

        return $user;
    }

    // Xử lý ảnh thumbnail
    private function handleThumbnail($file)
    {
        $fileName = HandleFileTrait::generateName($file);
        HandleFileTrait::uploadFile($file, $fileName, 'courses');
        
        return $fileName;
    }

    // Tạo chương giới thiệu cho khóa học
    private function createIntroductionChapter($courseId)
    {
        $dataChapter = [
            'name' => 'Giới Thiệu',
            'course_id' => $courseId
        ];

        return $this->chapterRepo->create($dataChapter);
    }
}
