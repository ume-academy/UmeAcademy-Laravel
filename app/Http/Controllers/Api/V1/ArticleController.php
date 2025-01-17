<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Article\StoreArticleRequest;
use App\Http\Requests\Article\UpdateArticleRequest;
use App\Http\Resources\Article\ArticleResource;
use App\Http\Resources\Article\DetailArticleResource;
use App\Services\ArticleService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct(
        private ArticleService $articleService
    ){}

    public function getAllArticle(Request $req) {
        try {
            $perPage = $req->input('per_page', 10);
            $status = $req->input('status');
            $articles = $this->articleService->getAllArticle($perPage, $status);
            
            return ArticleResource::collection($articles);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getArticle($id) {
        try {
            $article = $this->articleService->getArticle($id);
            
            return new DetailArticleResource($article);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAllArticlePublished(Request $req) {
        try {
            $perPage = $req->input('per_page', 10);
            $articles = $this->articleService->getAllArticlePublished($perPage);
            
            return ArticleResource::collection($articles);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getArticlePublished($id) {
        try {
            $article = $this->articleService->getArticlePublished($id);
            
            return new DetailArticleResource($article);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createArticle(StoreArticleRequest $req) {
        try {
            $data = $req->all();

            $article = $this->articleService->createArticle($data);
            
            return new ArticleResource($article);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateArticle(UpdateArticleRequest $req, $id) {
        try {
            $data = $req->all();

            $article = $this->articleService->updateArticle($id, $data);
            
            return new ArticleResource($article);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteArticle($id) {
        try {
            $article = $this->articleService->deleteArticle($id);
            
            if($article) {
                return response()->json(['message' => 'Xóa thành công']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function uploadImage(Request $req) {
        try {
            $data = $req->all();

            return $image = $this->articleService->uploadImage($data);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
