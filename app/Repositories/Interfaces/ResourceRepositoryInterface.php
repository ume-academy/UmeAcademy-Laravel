<?php

namespace App\Repositories\Interfaces;

interface ResourceRepositoryInterface
{
    public function create(array $data);
    public function delete(int $id);
    public function deleteResource(int $id);
}
