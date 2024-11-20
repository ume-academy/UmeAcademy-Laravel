<?php

namespace App\Repositories\Interfaces;

interface FeePlatformRepositoryInterface
{
    public function getFee();
    public function getFeeTeacher(int $id);
}
