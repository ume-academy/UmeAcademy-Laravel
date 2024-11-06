<?php

namespace App\Repositories\Interfaces;

interface CourseRepositoryInterface
{
    public function getByTeacher(int $id, int $perPage);
    public function create(array $data);
    public function find(int $id);
}