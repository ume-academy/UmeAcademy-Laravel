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

    public function delete(int $id) {
        $resource = Resource::findOrFail($id);

        return $resource->delete();
    }

    public function deleteResource(int $id) {
        $resource = Resource::findOrFail($id);

        return $resource->delete();
    }
}
