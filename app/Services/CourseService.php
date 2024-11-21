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
            $teacher = $this->validateTeacher();

            // Xử lý ảnh thumbnail
            $data['thumbnail'] = $this->handleThumbnail($data['thumbnail']);
            $data['teacher_id'] = $teacher->id;

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
        $teacher = $this->validateTeacher();

        return $this->courseRepo->getByTeacher($teacher->id, $perPage);
    }

    public function getInfoCourse($id) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
    
            $course = $this->courseRepo->getById($id);
    
            $is_wishlist = $course->checkWishlist($user->id);
            $is_enrolled = $course->checkEnrolled($user->id);
            
            $course['is_wishlist'] = $is_wishlist;
            $course['is_enrolled'] = $is_enrolled;

            return $course;
    
        } catch (JWTException $e) {
            $course = $this->courseRepo->getById($id); 
    
            $course['is_wishlist'] = false;
            $course['is_enrolled'] = false;

            return $course;
        }
    }

    public function getStatisticCourse($id) {
        return $this->courseRepo->getById($id); 
    }

    public function getContentCourse($id) {
        return $this->courseRepo->getById($id); 
    }

    public function getOverviewCourse($id) {
        return $this->courseRepo->getById($id); 
    }

    public function getPurchasedCourseContent($id) {
        $user = JWTAuth::parseToken()->authenticate();
    
        $course = $this->courseRepo->getById($id);

        if($course->checkEnrolled($user->id)) {
            // danh sách các bài học đã hoàn thành
            $completedLessons = $this->courseRepo->completedLessons($course->id, $user->id);
            
            $course['completed_lesson'] = $completedLessons->count();

            $course['completed_lesson_in_chapter'] = $course->chapters->map(function ($chapter) use ($user) {
                // Lấy số bài học đã hoàn thành trong chapter này
                $completedInChapter = $chapter->completedLessonsCount($user->id);
    
                return $completedInChapter;
            });
            
            return $course;
        } else {
            throw new \Exception('Bạn chưa mua khóa học'); 
        }
    }

    public function getPurchasedCourses($perPage) {
        $user = JWTAuth::parseToken()->authenticate();

        return $this->courseRepo->getCourseOfStudent($user, $perPage);
    }

    public function getCourse($id) {
        $teacher = $this->validateTeacher();

        // Kiểm tra quyền sở hữu của khóa học
        $this->validateCourse($teacher, $id);

        return $this->courseRepo->find($id);
    }
    
    public function updateCourse($id, $data) {
        $teacher = $this->validateTeacher();

        // Kiểm tra quyền sở hữu của khóa học
        $this->validateCourse($teacher, $id);

        $course = $this->courseRepo->find($id);      

        // Kiểm tra nếu không up ảnh mới thì sẽ dùng lại ảnh cũ 
        if (isset($data['thumbnail'])) {
            $data['thumbnail'] = $this->handleThumbnail($data['thumbnail']);
        } else {
            $data['thumbnail'] = $course->thumbnail;
        }

        if(isset($data['video'])) {
            $data['video'] = $this->handleVideo($data['video']);
        }

        // Cập nhật vào db
        $this->courseRepo->update($id, $data);

        $updatedCourse = $this->courseRepo->find($id);
        return $updatedCourse;
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

    // Xử lý ảnh video
    private function handleVideo($file)
    {
        $fileName = HandleFileTrait::generateName($file);
        HandleFileTrait::uploadFile($file, $fileName, 'courses', true);
        
        return $fileName;
    }
}
