<?php

namespace App\Http\Controllers\Api\V1\Course;

use App\Contracts\CreateCourseInterface;
use App\Contracts\GetCoursesOfTeacherInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Resources\Course\OverviewCourseResource;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(
        private CreateCourseInterface $createCourseService,
        private GetCoursesOfTeacherInterface $getCoursesOfTeacherService,
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

            $course = $this->createCourseService->createCourse($data);

            return new OverviewCourseResource($course);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCoursesOfTeacher(Request $req) {
        try {
            $perPage = $req->input('per_page', 10);

            $courses = $this->getCoursesOfTeacherService->getCoursesOfTeacher($perPage);

            return OverviewCourseResource::collection($courses);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
