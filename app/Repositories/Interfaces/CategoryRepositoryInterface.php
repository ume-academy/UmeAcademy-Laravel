<?php

namespace App\Repositories\Interfaces;

interface CategoryRepositoryInterface
{
    // 
    public function all($perPage);
    public function create($data);
}
