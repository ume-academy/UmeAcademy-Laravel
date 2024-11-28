<?php

namespace App\Repositories\Interfaces;

interface CategoryRepositoryInterface
{
    // 
    public function all($perPage);
    public function create($data);
    public function getById(int $id);
    public function update(int $id, array $data);
    public function delete(int $id);
}
