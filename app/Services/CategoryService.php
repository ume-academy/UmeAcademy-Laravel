<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Spatie\Permission\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;

class CategoryService
{
    // 
    public function __construct(
        private CategoryRepositoryInterface $CategoryRepository,
    ){}

    public function getAllCategories($perPage){
        return $this->CategoryRepository->all($perPage);
    }

    public function createCategory($data){
        $user = JWTAuth::parseToken()->authenticate();

        if(!$user || !$user->hasRole('admin')) {
            throw new AuthorizationException('Unauthorized');
        }

        return $this->CategoryRepository->create($data);
    }

    public function getCategory($id) {
        $user = JWTAuth::parseToken()->authenticate();

        if(!$user || !$user->hasRole('admin')) {
            throw new AuthorizationException('Unauthorized');
        }

        return $this->CategoryRepository->getById($id);
    }

    public function updateCategory($id, $data) {
        $user = JWTAuth::parseToken()->authenticate();

        if(!$user || !$user->hasRole('admin')) {
            throw new AuthorizationException('Unauthorized');
        }

        return $this->CategoryRepository->update($id, $data);
    }

    public function deleteCategory($id) {
        $user = JWTAuth::parseToken()->authenticate();

        if(!$user || !$user->hasRole('admin')) {
            throw new AuthorizationException('Unauthorized');
        }

        return $this->CategoryRepository->delete($id);
    }
}
