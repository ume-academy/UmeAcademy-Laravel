<?php 

namespace App\Services\Category;

use App\Helpers\ImageHelper;
use App\Repositories\Category\CategoryRepositoryInterface;
use Exception;

class CategoryService {
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function listCategory($perPage) {
        if ($perPage <= 0) {
            throw new Exception('Per_page phải là số nguyên dương');
        }
        try {
            return $this->categoryRepository->getAll($perPage);
        } catch (Exception $e) {
            throw new Exception('Lỗi khi lấy danh sách category: ' . $e->getMessage());
        }
    }
}