<?php

namespace App\Repositories;

use App\Models\FeePlatform;
use App\Models\TeacherFee;
use App\Repositories\Interfaces\FeePlatformRepositoryInterface;

class FeePlatformRepository implements FeePlatformRepositoryInterface
{
    public function getFee() {
        return FeePlatform::findOrFail(1)->fee;
    }

    public function getFeeTeacher(int $id)
    {
        return TeacherFee::where('teacher_id', $id)->first()->fee ?? $this->getFee();
    }

    public function updateFee(int $id, array $data)
    {
        $fee = FeePlatform::findOrFail($id);
        return $fee->update($data);
    }

    public function getById(int $id) {
        return FeePlatform::findOrFail($id);
    }

    public function updateFeeTeacher(int $id, array $data)
    {
        return TeacherFee::updateOrCreate(
            ['teacher_id' => $id], 
            $data                
        );
    }
}
