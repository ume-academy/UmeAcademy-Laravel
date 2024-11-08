<?php

namespace App\Services;

use App\Repositories\Interfaces\ReviewRepositoryInterface;

class ReviewService
{
    public function __construct(
        private ReviewRepositoryInterface $reviewRepo
    ){}

    public function getReviewCourse($id, $perPage) {
        return $this->reviewRepo->getReviewByCourse($id, $perPage);
    }
}
