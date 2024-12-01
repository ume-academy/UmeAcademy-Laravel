<?php

namespace App\Repositories\Interfaces;

interface StudentWalletRepositoryInterface
{
    public function getByStudentId(int $id);
}
