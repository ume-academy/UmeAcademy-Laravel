<?php

namespace App\Http\Controllers\API\V1\Category;

use App\Contracts\CategoryServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\Category\CategoryResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function __construct(
        
        private CategoryServiceInterface $categoryService)
    {}
    public function listCategories(Request $req)
    {
        try {
            $perPage = $req->input('per_page', 10);
            $data = $this->categoryService->getAllCategories($perPage);
            return CategoryResource::collection($data,$perPage);
        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Lấy danh sách Category không thành công",
                "errors" => $e->getMessage()
            ], 400);
        }
        
    } 

}
