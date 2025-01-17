<?php

namespace App\Repositories;

use App\Models\Course;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class CourseRepository implements CourseRepositoryInterface
{
    public function create(array $data)
    {
        return Course::create($data);
    }

    // Tìm được cả khóa học nháp ...
    public function find(int $id) {
        return Course::findOrFail($id);
    }

    public function getByTeacher(int $id, int $perPage) {
        return Course::where('teacher_id', $id)->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getCourseOfTeacher(int $id) {
        return Course::where('teacher_id', $id)->orderBy('created_at', 'desc')->where('status', 2)->get();
    }

    // Chỉ tìm được khóa học đã xuất bản
    public function getById(int $id) {
        return Course::where('status', 2)->findOrFail($id);
    }

    // Lấy danh sách bài học đã hoàn thành của user trong một khóa học
    public function completedLessons(int $courseId, int $userId)
    {
        $course = $this->getById($courseId);
        return $course->lessons()
            ->whereHas('lessonCompleted', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->get();
    }

    // Đồng bộ dữ liệu vào bảng course_enrolled
    public function syncCourseEnrolled(Course $course, array $userIds)
    {
        return $course->courseEnrolled()->attach($userIds);
    }

    // Đồng bộ dữ liệu vào bảng wishlist
    public function syncCourseWishlist(Course $course, array $userIds)
    {
        return $course->wishList()->attach($userIds);
    }

    public function removeCourseWishlist(Course $course, array $userIds)
    {
        return $course->wishList()->detach($userIds);
    }

    public function getWishlistByUser(int $id, $perPage)
    {
        return Course::whereHas('wishList', function ($query) use ($id) {
            $query->where('user_id', $id);
        })->orderBy('created_at', 'desc')->paginate($perPage);
    }

    // Lấy khóa học đã mua của học sinh
    public function getCourseOfStudent($user, $perPage) {
        return $user->enrolledCourses()
            ->withPivot('created_at')
            ->orderBy('pivot_created_at', 'desc')
            ->paginate($perPage);
    }

    public function update(int $id, array $data) {
        $course = $this->find($id);

        return $course->update($data);
    }

    public function delete(int $id) {
        $course = $this->find($id);

        return $course->delete();
    }
    
    public function getAllCoursePublic($perPage) {
        return Course::where('status', 2)->orderBy('created_at', 'desc')->paginate($perPage);
    }
    
    public function updateStatus(int $id, $status) {
        $course = $this->find($id);

        $course->status = $status;
        return $course->save();
    }

    public function getByCategory(int $id, $perPage) {
        return Course::where('category_id', $id)->where('status', 2)->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function filter($params)
    {
        $query = Course::query();

        // Lọc theo tên danh mục
        if (!empty($params['categories'])) {
            $query->whereHas('category', function ($q) use ($params) {
                $q->whereIn('name', $params['categories']);
            });
        }
        
        // Lọc theo từ khóa
        if (!empty($params['name'])) {
            $query->where('name', 'like', '%' . $params['name'] . '%');
        }

        // Lọc theo giá
        if (!empty($params['price_min'])) {
            $query->where('price', '>=', $params['price_min']);
        }
    
        if (!empty($params['price_max'])) {
            $query->where('price', '<=', $params['price_max']);
        }

        // Lọc theo trình độ
        if (!empty($params['levels'])) {
            $query->whereHas('level', function ($q) use ($params) {
                $q->whereIn('name', $params['levels']);  
            });
        }

        $query->where('status', 2);

        $courses = $query->orderBy('created_at', 'desc')->get();

        if (!empty($params['rating'])) {
            $courses = $courses->filter(function ($course) use ($params) {
                return $course->rating >= $params['rating'];
            });
        }

        $page = $params['page'] ?? 1;
        $perPage = $params['per_page'] ?? 10;

        $paginatedCourses = new LengthAwarePaginator(
            $courses->forPage($page, $perPage),
            $courses->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return $paginatedCourses;
    }

    public function getAllCourse($perPage, $status = null) {
        $query = Course::orderBy('created_at', 'desc'); // Sắp xếp theo ngày tạo
    
        // Lọc theo status nếu có
        if ($status !== null) {
            switch ($status) {
                case 'draft':
                    $query->where('status', Course::DRAFT);
                    break;
                case 'pending':
                    $query->where('status', Course::PENDING);
                    break;
                case 'published':
                    $query->where('status', Course::PUBLISHED);
                    break;
                default:
                    break;
            }
        }
    
        return $query->paginate($perPage);
    }

    public function getTop5CourseBestSeller(int $id) {
        $courses = Course::where('teacher_id', $id)->get();
    
        // Sắp xếp các khóa học theo total_student và lấy 5 bản ghi đầu tiên
        $topCourses = $courses->sortByDesc(function ($course) {
            return $course->total_student;
        })->take(5);
    
        return $topCourses;
    }
}
