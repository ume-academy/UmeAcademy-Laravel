<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\User;
use App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    // 
    public function all($perPage)
    {
        return Category::paginate($perPage);
    }
    public function create($data) {
       
        return Category::create($data);
    }
}
