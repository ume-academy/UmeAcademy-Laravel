<?php

namespace App\Http\Controllers\Api\V1\Course;

use App\Contracts\CreateCourseServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Resources\Course\OverviewCourseResource;

class CourseController extends Controller
{
    public function __construct(
        private CreateCourseServiceInterface $createCourseService,
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
}
