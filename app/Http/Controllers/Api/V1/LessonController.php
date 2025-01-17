<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lesson\StoreLessonRequest;
use App\Http\Requests\Lesson\UpdateLessonRequest;
use App\Http\Requests\Resource\StoreResourceRequest;
use App\Http\Requests\Video\StoreVideoRequest;
use App\Http\Resources\Lesson\LessonResource;
use App\Http\Resources\Resource\ResourceResource;
use App\Http\Resources\Video\VideoResource;
use App\Services\LessonService;
use App\Services\ResourceService;
use App\Services\VideoService;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function __construct(
        private LessonService $lessonService,
        private VideoService $videoService,
        private ResourceService $resourceService,
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
                // 'video' => $req->video,
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

    public function updateVideo(Request $req, $id, $chapterId, $lessonId) {
        try {
            $data = [
                'is_preview' => $req->input('is_preview', 0),
                'course_id' => $id,
                'chapter_id' => $chapterId,
                'lesson_id' => $lessonId,
            ];
            $video = $this->videoService->updateVideo($data);

            return new VideoResource($video);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteVideo($id, $chapterId, $lessonId) {
        try {
            $data = [
                'course_id' => $id,
                'chapter_id' => $chapterId,
                'lesson_id' => $lessonId,
            ];
            $video = $this->videoService->deleteVideo($data);
            
            if($video) {
                return response()->json(['message' => 'Xóa video thành công']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createResource(StoreResourceRequest $req, $id, $chapterId, $lessonId) {
        try {
            $data = [
                'resource' => $req->file('name'),
                'course_id' => $id,
                'chapter_id' => $chapterId,
                'lesson_id' => $lessonId,
            ];

            $resource = $this->resourceService->createResource($data);

            return new ResourceResource($resource);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteResource($id, $chapterId, $lessonId, $resourceId) {
        try {
            $data = [
                'course_id' => $id,
                'chapter_id' => $chapterId,
                'lesson_id' => $lessonId,
            ];

            $resource = $this->resourceService->deleteResource($resourceId, $data);
            
            if($resource) {
                return response()->json(['message' => 'Xóa tài nguyên thành công']);
            }
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

    public function deleteLesson($id, $chapterId, $lessonId) {
        try {
            $data['chapter_id'] = $chapterId;
            $data['course_id'] = $id;

            $lesson = $this->lessonService->deleteLesson($lessonId, $data);

            if($lesson) {
                return response()->json(['message' => 'Xóa bài học thành công'], 200);
            }
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
