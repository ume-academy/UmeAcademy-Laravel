<?php

namespace App\Repositories\Interfaces;

interface VideoRepositoryInterface
{
    public function create(array $data);
    public function updateVideo(int $id, bool $preview);
    public function deleteVideo(int $id);
}
