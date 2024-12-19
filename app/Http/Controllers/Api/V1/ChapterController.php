<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chapter\StoreChapterRequest;
use App\Http\Requests\Chapter\UpdateChapterRequest;
use App\Http\Resources\Chapter\ChapterResource;
use App\Services\ChapterService;

class ChapterController extends Controller
{
    public function __construct(
        private ChapterService $chapterService,
    ){}

    public function createChapter(StoreChapterRequest $req, $id) {
        try {
            $data = $req->only([
                'name',
            ]);
            $data['course_id'] = $id;

            $chapter = $this->chapterService->createChapter($data);

            return new ChapterResource($chapter);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateChapter(UpdateChapterRequest $req, $id, $chapterId) {
        try {
            $data = $req->only(['name']);
            $data['course_id'] = $id;

            $chapter = $this->chapterService->updateChapter($chapterId, $data);

            if($chapter) {
                return response()->json(['message' => 'Cập nhật chương học thành công'], 200);
            }
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteChapter($id, $chapterId) {
        try {
            $data['course_id'] = $id;

            $chapter = $this->chapterService->deleteChapter($chapterId, $data);

            if($chapter) {
                return response()->json(['message' => 'Xóa chương học thành công'], 200);
            }
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
