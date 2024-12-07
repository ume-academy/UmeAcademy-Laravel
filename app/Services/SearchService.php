<?php

namespace App\Services;

use App\Repositories\Interfaces\CourseRepositoryInterface;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class SearchService
{
    public function __construct(
        private CourseRepositoryInterface $courseRepo
    ){}

    public function searchByCategory($id, $perPage) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
    
            $courses = $this->courseRepo->getByCategory($id, $perPage);
    
            foreach ($courses as $course) {
                $is_wishlist = $course->checkWishlist($user->id);
                $is_enrolled = $course->checkEnrolled($user->id);
                
                $course['is_wishlist'] = $is_wishlist;
                $course['is_enrolled'] = $is_enrolled;
            }
    
            return $courses;
    
        } catch (JWTException $e) {
            $courses = $this->courseRepo->getByCategory($id, $perPage);
    
            foreach ($courses as $course) {
                $course['is_wishlist'] = false;
                $course['is_enrolled'] = false;
            }
    
            return $courses;
        }
    }
    

    public function searchCourse($data)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $params = [
                'categories' => isset($data['categories']) ? explode(',', $data['categories']) : [],
                'name'       => $data['name'] ?? null,
                'price'      => $data['price'] ?? null,
                'rating'     => $data['rating'] ?? null,
                'levels'     => isset($data['levels']) ? explode(',', $data['levels']) : [],
                'per_page'   => $data['per_page'] ?? 10,
            ];

            $courses = $this->courseRepo->filter($params);

            foreach ($courses as $course) {
                $is_wishlist = $course->checkWishlist($user->id);
                $is_enrolled = $course->checkEnrolled($user->id);
                
                $course['is_wishlist'] = $is_wishlist;
                $course['is_enrolled'] = $is_enrolled;
            }

            return $courses;

        } catch (JWTException $e) {
            $params = [
                'categories' => isset($data['categories']) ? explode(',', $data['categories']) : [],
                'name'       => $data['name'] ?? null,
                'price'      => $data['price'] ?? null,
                'rating'     => $data['rating'] ?? null,
                'levels'     => isset($data['levels']) ? explode(',', $data['levels']) : [],
                'per_page'   => $data['per_page'] ?? 10,
            ];

            $courses = $this->courseRepo->filter($params);

            foreach ($courses as $course) {
                $course['is_wishlist'] = false;
                $course['is_enrolled'] = false;
            }

            return $courses;
        }
    }
}
