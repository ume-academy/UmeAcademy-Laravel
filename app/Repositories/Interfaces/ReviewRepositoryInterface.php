<?php

namespace App\Repositories\Interfaces;

interface ReviewRepositoryInterface
{
    public function getReviewByCourse(int $id, int $perPage);
    public function create(array $data);
}
