<?php

namespace App\Services;

use App\Models\Review;
use App\Models\Transaction;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\ReviewRepositoryInterface;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReviewService
{
    public function __construct(
        private ReviewRepositoryInterface $reviewRepo,
        private CourseRepositoryInterface $courseRepo
    ){}

    public function getReviewCourse($id, $perPage) {
        return $this->reviewRepo->getReviewByCourse($id, $perPage);
    }

    public function createReviewCourse($id, $data) {
        $user = JWTAuth::parseToken()->authenticate();
        $course = $this->courseRepo->getById($id);
        
        $data = [
            'content' => $data['content'],
            'rating' => $data['rating'],
            'course_id' => $course->id,
            'user_id' => $user->id,
        ];

        $countTransaction = Transaction::where('user_id', $user->id)
                            ->where('course_id', $course->id)
                            ->where('status', 'success')->count();
                            
        $countReview = $course->checkReview($user->id)->count();
        if($countReview >= $countTransaction) {
            throw new \Exception('Bạn đã đánh giá khóa học này rồi!');
        }

        if($course->checkEnrolled($user->id)) {
            $review = $this->reviewRepo->create($data);
        }

        return $review;
    }
}
