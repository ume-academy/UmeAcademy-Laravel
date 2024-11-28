<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Course\CourseResource;
use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(
        private SearchService $searchService
    ){}

    public function searchByCategory(Request $req, $id) {
        try {
            $perPage = $req->input('per_page', 10);

            $courses = $this->searchService->searchByCategory($id, $perPage);
            
            return CourseResource::collection($courses);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
