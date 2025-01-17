<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\StoreReviewRequest;
use App\Http\Resources\Review\ReviewResource;
use App\Services\ReviewService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(
        private ReviewService $reviewService
    ){}

    public function getReviewCourse(Request $req, $id) {
        try {
            $perPage = $req->input('per_page', 4);

            $reviews = $this->reviewService->getReviewCourse($id, $perPage);
            
            return ReviewResource::collection($reviews);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createReviewCourse(StoreReviewRequest $req, $id) {
        try {
            $data = $req->all();
            return $review = $this->reviewService->createReviewCourse($id, $data);
            
            return new ReviewResource($review);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
