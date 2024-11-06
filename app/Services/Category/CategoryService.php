<?php

namespace App\Services\Category;

use App\Contracts\CategoryServiceInterface;
use App\Repositories\CategoryRepository;
use App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryService implements CategoryServiceInterface
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
    ) {}

    public function getAllCategories(int $perPage = 10)   
    {
        return $this->categoryRepository->getAllCategories($perPage);
    }
}