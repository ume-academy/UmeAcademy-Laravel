<?php

namespace App\Http\Controllers\API\V1\Category;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category\CategoryResource;
use App\Services\Category\CategoryService;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function listCategory(Request $req) {
        try {
            $perPage = $req->input('per_page', 10);
            $data = $this->categoryService->listCategory($perPage);

            return CategoryResource::collection($data)->additional([
                "status" => true,
                "message" => 'Lấy danh sách Category thành công'
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Lấy danh sách Category không thành công",
                "errors" => $e->getMessage()
            ], 400);
        }
    }  
}
