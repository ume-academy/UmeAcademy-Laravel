<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryService $categoryService,
    ) {
    }
    public function getAllCategories(Request $req)
    {
        try {
            $perPage = $req->input('per_page', 10);
            $categories = $this->categoryService->getAllCategories($perPage);
            return CategoryResource::collection($categories);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
    public function storeCategories(StoreCategoryRequest $req)
    {
        try {
            $data = ['name' => $req->input('name'), 
            'parent_id' => $req->input('parent_id')] ;
            $category = $this->categoryService->createCategory($data);
            return response()->json([
                'status' => 'true',  
                'message' => 'Tạo mới Category thành công',  
                'data' => new CategoryResource($category)]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function getCategory($id) {
        try {
            $category = $this->categoryService->getCategory($id);
            return new CategoryResource($category);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateCategory(UpdateCategoryRequest $req, $id) {
        try {
            $data = $req->only(['name']);

            $category = $this->categoryService->updateCategory($id, $data);

            if($category) {
                return response()->json(['message' => 'Cập nhật thành công']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteCategory($id) {
        try {
            $category = $this->categoryService->deleteCategory($id);

            if($category) {
                return response()->json(['message' => 'Xóa thành công']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}