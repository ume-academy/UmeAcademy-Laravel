<?php

namespace App\Services;

use App\Models\Course;
use App\Repositories\Interfaces\ChapterRepositoryInterface;
use App\Repositories\Interfaces\CourseApprovalRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Traits\HandleFileTrait;
use App\Traits\ValidationTrait;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CourseService
{
    use HandleFileTrait, ValidationTrait;

    public function __construct(
        private CourseRepositoryInterface $courseRepo,
        private ChapterRepositoryInterface $chapterRepo,
        private CourseApprovalRepositoryInterface $courseApprovalRepo,
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

            // Tạo mảng các ID bài học đã hoàn thành theo chapter
            $completedLessonInChapter = $course->chapters->map(function ($chapter) use ($user) {
                $completedInChapter = $chapter->completedLessons($user->id);
                return $completedInChapter->pluck('id');
            });
            
            $course['completed_lesson_in_chapter'] = $completedLessonInChapter->map(fn($ids) => $ids->count())->toArray();
            $course['lesson_completed_ids'] = $completedLessonInChapter->flatten()->unique()->values()->toArray();

            return $course;
        } else {
            throw new \Exception('Bạn chưa mua khóa học'); 
        }
    }

    public function getPurchasedCourses($perPage) {
        $user = JWTAuth::parseToken()->authenticate();
    
        $courses = $this->courseRepo->getCourseOfStudent($user, $perPage);
    
        foreach ($courses as &$course) {
            // Lọc transaction có status = 'success'
            $transaction = $course->transactions->where('user_id', $user->id)->firstWhere('status', 'success'); 
    
            if ($transaction) {
                // Kiểm tra điều kiện thời gian giao dịch
                $course['refund'] = $transaction->created_at->diffInDays(now()) > 7 ? false : true;
                $course['transaction_code'] = $transaction->transaction_code;
            } else {
                $course['refund'] = null;
                $course['transaction_code'] = null;
            }
        }
    
        return $courses;
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

        if($course->status == 2) {
            throw new \Exception('Không thể cập nhật khóa học vì khóa học đã được phê duyệt.');
        }

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

    public function requestApprovalCourse($id) {
        $teacher = $this->validateTeacher();

        // Kiểm tra quyền sở hữu của khóa học
        $this->validateCourse($teacher, $id);

        $course = $this->courseRepo->find($id);
        $courseValidator = Validator::make($course->toArray(), [
            'name' => 'required',
            'summary' => 'required',
            'price' => 'required|numeric|min:10000',
            'thumbnail' => 'required',
            'course_requirement' => 'required',
            'course_learning_benefit' => 'required',
            'level_id' => 'required',
            'category_id' => 'required',
            'total_lesson' => 'integer|min:5',
        ], [
            'name.required' => 'name không được bỏ trống.',

            'summary.required' => 'summary không được bỏ trống.',

            'price.required' => 'price không được bỏ trống.',
            'price.numeric' => 'price phải là số.',
            'price.min' => 'Giá thấp nhất là 10000.',

            'thumbnail.required' => 'thumbnail không được bỏ trống.',

            'course_requirement.required' => 'Khóa học phải có yêu cầu',
            'course_learning_benefit.required' => 'Khóa học phải có lợi ích',

            'level.required' => 'Cấp độ là bắt buộc',
            'category.required' => 'Danh mục là bắt buộc',

            'total_lesson.integer' => 'Tổng bài học phải là số nguyên', 
            'total_lesson.min' => 'Khóa học phải có ít nhất 5 bài học', 
        ]);

        if ($courseValidator->fails()) {
            return [
                'errors' => $courseValidator->errors()->toArray(),
            ];
        }
        $lessons = $course->lessons;

        // Kiểm tra mỗi lesson
        foreach ($lessons as $lesson) {
            if (empty($lesson->video)) {
                throw new \Exception("Lesson '{$lesson->name}' không có video.");
            }
        }

        $data = [
            'teacher_id' => $teacher->id,
            'course_id' => $course->id,
        ];
        
        $courseApproval = $this->courseApprovalRepo->create($data);
        if($courseApproval) {
            $this->courseRepo->updateStatus($course->id, 1);
        }

        return $courseApproval;
    }

    public function updateTargetCourse($id, $data) {
        $teacher = $this->validateTeacher();

        // Kiểm tra quyền sở hữu của khóa học
        $this->validateCourse($teacher, $id);

        $course = $this->courseRepo->find($id);

        if($course->status == 2) {
            throw new \Exception('Không thể cập nhật khóa học vì khóa học đã được phê duyệt.');
        }

        $data['course_requirement'] = $data['data']['course_requirement'];
        $data['course_learning_benefit'] = $data['data']['course_learning_benefit'];

        // Cập nhật vào db
        $this->courseRepo->update($id, $data);

        $updatedCourse = $this->courseRepo->find($id);
        return $updatedCourse;
    }

    public function getAllCoursePublic($perPage) {
        return $this->courseRepo->getAllCoursePublic($perPage);
    }

    public function getStudentsOfCourse($id, $perPage) {
        $teacher = $this->validateTeacher();

        $course = $this->courseRepo->getById($id);

        // Kiểm tra quyền sở hữu của khóa học
        $this->validateCourse($teacher, $id);

        $students = $course->courseEnrolled()->withPivot('created_at')->orderBy('pivot_created_at', 'desc')->paginate($perPage);

        // Duyệt qua từng học viên và thêm thông tin về tiến độ
        $students->getCollection()->transform(function ($user) use ($course) {
            $user->progress = floor(
                $this->courseRepo->completedLessons($course->id, $user->pivot->user_id)->count() / $course->total_lesson * 100
            );
            
            return $user;
        });

        return $students;
    }

    public function approval($id, $status) {
        $course = $this->courseRepo->find($id);

        if($course->status == 2) {
            throw new \Exception('Khóa học đã được phê duyệt');
        }

        return $this->courseRepo->updateStatus($id, $status);
    }

    public function getAllCourse($perPage, $status) {
        return $this->courseRepo->getAllCourse($perPage, $status);
    }

    public function getDetailCourse($id) {
        return $this->courseRepo->find($id);
    }

    public function addWishlist($id) {
        $course = $this->courseRepo->getById($id);

        $user = JWTAuth::parseToken()->authenticate();

        return $this->courseRepo->syncCourseWishlist($course, [$user->id]);
    }

    public function removeWishlist($id) {
        $course = $this->courseRepo->getById($id);

        $user = JWTAuth::parseToken()->authenticate();

        return $this->courseRepo->removeCourseWishlist($course, [$user->id]);
    }

    public function getWishlist($perPage) {
        $user = JWTAuth::parseToken()->authenticate();

        $courses = $this->courseRepo->getWishlistByUser($user->id, $perPage);

        foreach ($courses as $course) {
            $is_wishlist = $course->checkWishlist($user->id);
            $is_enrolled = $course->checkEnrolled($user->id);
            
            $course['is_wishlist'] = $is_wishlist;
            $course['is_enrolled'] = $is_enrolled;
        }

        return $courses;
    }

    public function coursePrice() {
        $maxPrice = Course::where('status', 2)->max('price'); // Lấy giá cao nhất
        $minPrice = Course::where('status', 2)->min('price'); // Lấy giá thấp nhất
    
        return [
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
        ];
    }

    public function getTop5CourseBestSeller() {
        $teacher = $this->validateTeacher();

        return $this->courseRepo->getTop5CourseBestSeller($teacher->id, 5);
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
        HandleFileTrait::uploadFile($file, $fileName, 'courses', 'videos');
        
        return $fileName;
    }
}
