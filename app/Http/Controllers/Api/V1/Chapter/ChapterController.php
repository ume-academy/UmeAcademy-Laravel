<?php

namespace App\Http\Controllers\Api\V1\Chapter;

use App\Contracts\CreateChapterServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chapter\StoreChapterRequest;
use App\Http\Resources\Chapter\ChapterResource;
use Exception;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    public function __construct(
        private CreateChapterServiceInterface $createChapterService,
    ){}

    public function createChapter(StoreChapterRequest $req, $id) {
        try {
            $data = $req->only([
                'name',
            ]);
            $data['course_id'] = $id;

            $chapter = $this->createChapterService->createChapter($data);

            return new ChapterResource($chapter);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
