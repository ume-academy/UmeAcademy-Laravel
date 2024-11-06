<?php

namespace App\Repositories\Interfaces;

interface CategoryRepositoryInterface
{
    // 
    public function getAllCategories(int $perPage=10);
}
