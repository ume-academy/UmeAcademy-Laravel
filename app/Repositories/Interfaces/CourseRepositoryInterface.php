<?php

namespace App\Repositories\Interfaces;

interface CourseRepositoryInterface
{
    public function create(array $data);
    public function find(int $id);
}
