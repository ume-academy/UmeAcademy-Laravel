<?php

namespace App\Repositories;

use App\Models\Review;
use App\Repositories\Interfaces\ReviewRepositoryInterface;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function getReviewByCourse(int $id, int $perPage) {
        return Review::where('course_id', $id)->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
