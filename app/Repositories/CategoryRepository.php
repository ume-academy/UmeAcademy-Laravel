<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\User;
use App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function all($perPage)
    {
        return Category::orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function create($data) {
       
        return Category::create($data);
    }

    public function getById(int $id)
    {
        return Category::findOrFail($id);
    }

    public function update(int $id, array $data)
    {
        $category = Category::findOrFail($id);

        return $category->update($data);
    }

    public function delete(int $id) {
        $category = Category::findOrFail($id);
        
        return $category->delete();
    }
}
