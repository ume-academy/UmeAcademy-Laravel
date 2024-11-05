<?php

namespace App\Repositories\Category;

use App\Models\Category;
use Exception;
use PHPUnit\Framework\MockObject\Stub\ReturnArgument;

class CategoryRepository implements CategoryRepositoryInterface {

    protected $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getAll($perPage)
    {
        try {
            return $this->category->paginate($perPage);
        } catch (Exception $e) {
            throw new Exception('Lỗi tìm nạp tất cả Category: ' . $e->getMessage());
        }
    }   
}