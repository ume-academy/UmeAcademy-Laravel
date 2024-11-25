<?php

namespace App\Repositories\Interfaces;

interface ChapterRepositoryInterface
{
    public function create(array $data);
    public function find(int $id);
    public function update(int $id, array $data);
}
