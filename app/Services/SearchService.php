<?php

namespace App\Services;

use App\Repositories\Interfaces\CourseRepositoryInterface;

class SearchService
{
    public function __construct(
        private CourseRepositoryInterface $courseRepo
    ){}

    public function searchByCategory ($id, $perPage) {
        return $this->courseRepo->getByCategory($id, $perPage);
    }

    public function searchCourse($data)
    {
        $params = [
            'categories' => isset($data['categories']) ? explode(',', $data['categories']) : [],
            'name'     => $data['name'] ?? null,
            'price'       => $data['price'] ?? null,
            'rating'      => $data['rating'] ?? null,
            'levels'       => isset($data['levels']) ? explode(',', $data['levels']) : [] ?? null,
            'per_page'    => $data['per_page'] ?? 10,
        ];
        
        return $this->courseRepo->filter($params);
    }

}
