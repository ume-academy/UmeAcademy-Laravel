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
}
