<?php

namespace App\Repositories\Interfaces;

interface FeePlatformRepositoryInterface
{
    public function getFee();
    public function getFeeTeacher(int $id);
    public function updateFee(int $id, array $data);
    public function getById(int $id);
}
