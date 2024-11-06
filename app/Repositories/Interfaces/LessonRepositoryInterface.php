<?php

namespace App\Repositories\Interfaces;

interface LessonRepositoryInterface
{
    public function create(array $data);
    public function find(int $id);
}