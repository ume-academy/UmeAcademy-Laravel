<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Http\Resources\Course\ContentCoursePurchasedResource;
use App\Http\Resources\Course\ContentCourseResource;
use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\Course\InfoTeacherCourseResource;
use App\Http\Resources\Course\OverviewCourseResource;
use App\Http\Resources\Course\StatisticCourseResource;
use App\Services\CourseService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(
        private CourseService $courseService,
    ){}

    public function createCourse(StoreCourseRequest $req) {
        try {
            $data = $req->only([
                'name', 
                'summary', 
                'thumbnail', 
                'category_id', 
                'level_id'
            ]);

            $course = $this->courseService->createCourse($data);
            
            return new CourseResource($course);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCoursesOfTeacher(Request $req) {
        try {
            $perPage = $req->input('per_page', 10);

            $courses = $this->courseService->getCoursesOfTeacher($perPage);

            return CourseResource::collection($courses);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getInfoCourse($id) {
        try {
            $course = $this->courseService->getInfoCourse($id);
            
            return (new CourseResource($course))->additional([
                'is_wishlist' => $course->is_wishlist,
                'is_enrolled' => $course->is_enrolled
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getStatisticCourse($id) {
        try {
            $course = $this->courseService->getStatisticCourse($id);
            
            return new StatisticCourseResource($course);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getContentCourse($id) {
        try {
            $course = $this->courseService->getContentCourse($id);
            
            return new ContentCourseResource($course);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getOverviewCourse($id) {
        try {
            $course = $this->courseService->getOverviewCourse($id);
            
            return new OverviewCourseResource($course);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCourseTeacherInformation($id) {
        try {
            $course = $this->courseService->getOverviewCourse($id);
            
            return new InfoTeacherCourseResource($course);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getPurchasedCourseContent($id) {
        try {
            $course = $this->courseService->getPurchasedCourseContent($id);
            
            return new ContentCoursePurchasedResource($course);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getPurchasedCourses(Request $req) {
        try {
            $perPage = $req->input('per_page', 8);

            $courses = $this->courseService->getPurchasedCourses($perPage);
            return CourseResource::collection($courses);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCourse($id) {
        try {
            $course = $this->courseService->getCourse($id);
            return new CourseResource($course);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateCourse(UpdateCourseRequest $req, $id) {
        try {
            $data = $req->all();
            
            $course = $this->courseService->updateCourse($id, $data);
            return new CourseResource($course);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
