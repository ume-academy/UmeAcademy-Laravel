<?php

namespace App\Http\Controllers\Api\V1\Lesson;

use App\Contracts\CreateLessonServiceInterface;
use App\Contracts\CreateVideoServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Lesson\StoreLessonRequest;
use App\Http\Requests\Video\StoreVideoRequest;
use App\Http\Resources\Lesson\LessonResource;
use App\Http\Resources\Video\VideoResource;

class LessonController extends Controller
{
    public function __construct(
        private CreateLessonServiceInterface $createLessonService,
        private CreateVideoServiceInterface $createVideoService
    ){}

    public function createLesson(StoreLessonRequest $req, $id, $chapterId) {
        try {
            $data = $req->only([
                'name',
            ]);
            $data['chapter_id'] = $chapterId;
            $data['course_id'] = $id;

            $lesson = $this->createLessonService->createLesson($data);

            return new LessonResource($lesson);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createVideo(StoreVideoRequest $req, $id, $chapterId, $lessonId) {
        try {
            $data = [
                'video' => $req->file('name'),
                'course_id' => $id,
                'chapter_id' => $chapterId,
                'lesson_id' => $lessonId,
            ];

            $video = $this->createVideoService->createVideo($data);

            return new VideoResource($video);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
