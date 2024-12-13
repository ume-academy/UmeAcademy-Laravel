<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function findById(int $id);
    public function findByEmail(string $email);
    public function create(array $data);
    public function update(int $id, array $data);
    // public function delete(int $id): bool;
    public function getAllUser($perPage);
    public function getAllTeacher($perPage);
    public function lock(int $id);
    public function unlock(int $id);

    public function updatePassword(int $userId, string $newPassword);
    public function getUserRoles(array $roles, $perPage);
    public function isSystemUser(int $id);
}
