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
            $user = JWTAuth::parseToken()->authenticate();

            if(!$user->teacher()->exists()) {
                throw new NotTeacherException();
            }

            $file = $data['thumbnail'];

            $fileName = HandleFileTrait::generateName($file); // Tạo tên cho file ảnh

            $data['thumbnail'] = $fileName;
            $data['teacher_id'] = $user->teacher->id;
            
            $course = $this->courseRepo->create($data);

            if($course) {
                HandleFileTrait::uploadFile($file, $fileName, 'courses');

                $dataChapter = [
                    'name' => 'Giới Thiệu',
                    'course_id' => $course->id
                ];

                $this->chapterRepo->create($dataChapter);
            }

            DB::commit(); 
            return $course;

        } catch (Exception $e) {
            DB::rollBack(); 
            throw new Exception('Lỗi khi tạo khóa học: ' . $e->getMessage());
        }
    }
}
