<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    // 
    public function all($perPage)
    {
        return Category::paginate($perPage);
    }
}
