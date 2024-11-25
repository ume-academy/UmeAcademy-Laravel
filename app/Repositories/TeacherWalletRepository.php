<?php

namespace App\Repositories;

use App\Models\TeacherWallet;
use App\Repositories\Interfaces\TeacherWalletRepositoryInterface;

class TeacherWalletRepository implements TeacherWalletRepositoryInterface
{
    public function create(array $data) {
        return TeacherWallet::create($data);
    }

    public function getByTeacher(int $id) {
        return TeacherWallet::where('teacher_id', $id)->first();
    }

    public function update(int $id, array $data) {
        $teacherWallet = TeacherWallet::findOrFail($id);

        return $teacherWallet->update($data);
    }

    public function getByTeacherId(int $id) {
        return TeacherWallet::where('teacher_id', $id)->first();
    }
}
