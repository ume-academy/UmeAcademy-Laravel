<?php

namespace App\Repositories\Interfaces;

interface RefreshTokenRepositoryInterface
{
    public function create(array $data);
    public function updateOrCreate(array $oldData, array $newData);
    public function findByToken(string $token);
    public function delete(string $token);
}
