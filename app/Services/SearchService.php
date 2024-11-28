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
}
