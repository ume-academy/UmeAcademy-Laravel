<?php

namespace App\Http\Controllers\Api\V1\Lesson;

use App\Contracts\CreateLessonServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\Lesson\LessonResource;
use Exception;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function __construct(
        private CreateLessonServiceInterface $createLessonService
    ){}

    public function createLesson(Request $req, $id, $chapterId) {
        try {
            $data = $req->only([
                'name',
            ]);
            $data['chapter_id'] = $chapterId;
            $data['course_id'] = $id;

            $lesson = $this->createLessonService->createLesson($data);

            return new LessonResource($lesson);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
