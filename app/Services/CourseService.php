<?php

namespace App\Services;

use App\Repositories\Interfaces\ChapterRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Traits\HandleFileTrait;
use App\Traits\ValidationTrait;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CourseService
{
    use HandleFileTrait, ValidationTrait;

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

        } catch (\Exception $e) {
            DB::rollBack(); 
            throw new \Exception('Lỗi khi tạo khóa học: ' . $e->getMessage());
        }
    }

    public function getCoursesOfTeacher($perPage)
    {
        $user = $this->validateTeacher();

        return $this->courseRepo->getByTeacher($user->teacher->id, $perPage);
    }

    public function getInfoCourse($id) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
    
            $course = $this->courseRepo->getInfoById($id);
    
            $is_wishlist = $course->wishlist()->where('user_id', $user->id)->exists();
            $is_enrolled = $course->courseEnrolled()->where('user_id', $user->id)->exists();
            
            $course['is_wishlist'] = $is_wishlist;
            $course['is_enrolled'] = $is_enrolled;

            return $course;
    
        } catch (JWTException $e) {
            $course = $this->courseRepo->getInfoById($id); 
    
            $course['is_wishlist'] = false;
            $course['is_enrolled'] = false;

            return $course;
        }
    }

    public function getStatisticCourse($id) {
        return $this->courseRepo->getInfoById($id); 
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
