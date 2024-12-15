<?php

namespace App\Services;

use App\Models\Article;
use App\Traits\HandleFileTrait;
use Tymon\JWTAuth\Facades\JWTAuth;

class ArticleService
{
    use HandleFileTrait;

    public function getAllArticle($perPage) {
        return Article::orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getAllArticlePublished($perPage) {
        return Article::where('status', 'published')->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getArticle($id) {
        return Article::findOrFail($id);
    }

    public function getArticlePublished($id) {
        return Article::where('status', 'published')->findOrFail($id);
    }

    public function createArticle($data) {
        $user = JWTAuth::parseToken()->authenticate();

        // Xử lý ảnh thumbnail
        $data['thumbnail'] = $this->handleThumbnail($data['thumbnail']);

        $data['user_id'] = $user->id;

        return Article::create($data);
    }

    public function updateArticle($id, $data) {
        $article = Article::findOrFail($id);

        $user = JWTAuth::parseToken()->authenticate();
        $data['user_id'] = $user->id;

        // Kiểm tra nếu không up ảnh mới thì sẽ dùng lại ảnh cũ 
        if (isset($data['thumbnail'])) {
            $data['thumbnail'] = $this->handleThumbnail($data['thumbnail']);
        } else {
            $data['thumbnail'] = $article->thumbnail;
        }

        // Cập nhật vào db
        $article->update($data);

        return $article;
    }

    public function deleteArticle($id) {
        $article = Article::findOrFail($id);

        return $article->delete();
    }

    public function uploadImage($data) {
        if ($data['upload']) {
            $image = $this->handleThumbnail($data['upload']);

            return response()->json([
                'url' => url('/images/articles/'. $image),
            ]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }

    private function handleThumbnail($file)
    {
        $fileName = HandleFileTrait::generateName($file);
        HandleFileTrait::uploadFile($file, $fileName, 'articles');
        
        return $fileName;
    }
}
