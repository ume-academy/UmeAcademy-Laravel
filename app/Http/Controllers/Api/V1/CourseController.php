<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Http\Resources\Course\ContentCoursePurchasedResource;
use App\Http\Resources\Course\ContentCourseResource;
use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\Course\DetailCourseResource;
use App\Http\Resources\Course\InfoTeacherCourseResource;
use App\Http\Resources\Course\OverviewCourseResource;
use App\Http\Resources\Course\StatisticCourseResource;
use App\Http\Resources\Course\StudentCourseResource;
use App\Http\Resources\UserResource;
use App\Services\CourseService;
use Illuminate\Database\QueryException;
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
            
            return $response = new CourseResource($course);

            return response()->json($response, 200);
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
            return new DetailCourseResource($course);
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

    public function requestApprovalCourse($id) {
        try {
            $this->courseService->requestApprovalCourse($id);
            return response()->json(['message' => 'Gửi yêu cầu phê duyệt khóa học thành công']);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateTargetCourse(Request $req, $id) {
        try {
            $data = $req->only('data');

            $course = $this->courseService->updateTargetCourse($id, $data);
            return new OverviewCourseResource($course);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCourseByIds(Request $req) {
        try {
            $ids = $req->input('ids');

            $courses = $this->courseService->getCourseByIds($ids);
            return CourseResource::collection($courses);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getStudentsOfCourse(Request $req, $id) {
        try {
            $perPage = $req->input('per_page', 10);

            $students = $this->courseService->getStudentsOfCourse($id, $perPage);

            return StudentCourseResource::collection($students);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function approval(Request $req, $id) {
        try {
            $status = $req->input('status');
           
            $course = $this->courseService->approval($id, $status);

            if($course) {
                return response()->json(['message' => 'Cập nhật thành công']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAllCourse(Request $req) {
        try {
            $perPage = $req->input('per_page', 10);

            $courses = $this->courseService->getAllCourse($perPage);

            return CourseResource::collection($courses);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getDetailCourse($id) {
        try {
            $course = $this->courseService->getDetailCourse($id);
            return new DetailCourseResource($course);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function addWishlist($id) {
        try {
            $isWishlist = $this->courseService->addWishlist($id);
            return response()->json(['data' => 'success']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return response()->json(['error' => 'Khóa học đã được yêu thích!',], 400); 
            }
            
            throw $e;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function removeWishlist($id) {
        try {
            $isWishlist = $this->courseService->removeWishlist($id);
            return response()->json(['data' => 'success']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return response()->json(['error' => 'Khóa học đã chưa yêu thích!',], 400); 
            }
            
            throw $e;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getWishlist(Request $req) {
        try {
            $perPage = $req->input('per_page', 10);

            $courses = $this->courseService->getWishlist($perPage);
            return CourseResource::collection($courses);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
