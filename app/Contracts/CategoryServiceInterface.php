<?php

namespace App\Contracts;

interface CategoryServiceInterface
{
    public function getAllCategories(int $perPage = 10);
}