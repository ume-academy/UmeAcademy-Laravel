<?php

namespace App\Repositories;

use App\Models\Level;
use App\Repositories\Interfaces\LevelRepositoryInterface;

class LevelRepository implements LevelRepositoryInterface
{
    public function getAll() {
        return Level::all();
    }
}
