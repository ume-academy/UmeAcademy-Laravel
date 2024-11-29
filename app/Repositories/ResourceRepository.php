<?php

namespace App\Repositories;

use App\Models\Resource;
use App\Repositories\Interfaces\ResourceRepositoryInterface;

class ResourceRepository implements ResourceRepositoryInterface
{
    public function create(array $data)
    {
        return Resource::create($data);
    }
}
