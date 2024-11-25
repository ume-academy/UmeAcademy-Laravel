<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function findById(int $id);
    public function findByEmail(string $email);
    public function create(array $data);
    public function update($userId,array $data);
    // public function update(int $id, array $data): object;
    // public function delete(int $id): bool;
}
