<?php

namespace App\Services;

use App\Repositories\Interfaces\LevelRepositoryInterface;

class LevelService
{
    public function __construct(
        private LevelRepositoryInterface $levelRepo
    ){}

    public function getAllLevel() {
        return $this->levelRepo->getAll();
    }
}
