<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lesson\StoreLessonRequest;
use App\Http\Requests\Lesson\UpdateLessonRequest;
use App\Http\Requests\Video\StoreVideoRequest;
use App\Http\Resources\Lesson\LessonResource;
use App\Http\Resources\Video\VideoResource;
use App\Services\LessonService;
use App\Services\VideoService;

class LessonController extends Controller
{
    public function __construct(
        private LessonService $lessonService,
        private VideoService $videoService
    ){}

    public function createLesson(StoreLessonRequest $req, $id, $chapterId) {
        try {
            $data = $req->only([
                'name',
            ]);
            $data['chapter_id'] = $chapterId;
            $data['course_id'] = $id;

            $lesson = $this->lessonService->createLesson($data);

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

            $video = $this->videoService->createVideo($data);

            return new VideoResource($video);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function markLessonCompleted($id, $chapterId, $lessonId) {
        try {
            $data = [
                'course_id' => $id,
                'chapter_id' => $chapterId,
                'lesson_id' => $lessonId,
            ];
            $this->lessonService->markLessonCompleted($data);

            return response()->json(['success' => 'Đã hoàn thành bài học']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateLesson(UpdateLessonRequest $req, $id, $chapterId, $lessonId) {
        try {
            $data = $req->only(['name']);
            $data['chapter_id'] = $chapterId;
            $data['course_id'] = $id;

            $lesson = $this->lessonService->updateLesson($lessonId, $data);

            if($lesson) {
                return response()->json(['message' => 'Cập nhật bài học thành công'], 200);
            }
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
