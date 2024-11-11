<?php 
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Http\Resources\Category\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller 
{
    public function __construct(
        private CategoryService $categoryService,
    ){}
    public function getAllCategories(Request $req){
        try {
            $perPage = $req->input('per_page', 10);
            $categories = $this->categoryService->getAllCategories($perPage);
            return CategoryResource::collection($categories);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
}