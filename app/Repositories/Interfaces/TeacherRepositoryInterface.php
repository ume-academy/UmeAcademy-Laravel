<?php

namespace App\Repositories\Interfaces;

interface TeacherRepositoryInterface
{
    public function create(array $data);
    public function getById($id);
    public function update(int $id, array $data);
}
