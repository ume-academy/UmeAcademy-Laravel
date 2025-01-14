<?php

namespace App\Repositories\Interfaces;

interface TeacherWalletRepositoryInterface
{
    public function create(array $data);
    public function getByTeacher(int $id);
    public function update(int $id, array $data);
    public function getByTeacherId(int $id);
}
