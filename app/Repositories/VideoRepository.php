<?php

namespace App\Repositories;

use App\Models\Video;
use App\Repositories\Interfaces\VideoRepositoryInterface;

class VideoRepository implements VideoRepositoryInterface
{
    public function create(array $data)
    {
        return Video::create($data);
    }
}
