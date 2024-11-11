<?php

namespace App\Services;

use App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryService
{
    // 
    public function __construct(
        private CategoryRepositoryInterface $CategoryRepository,
    ){}
    public function getAllCategories($perPage){
        return $this->CategoryRepository->all($perPage);
    }
}
