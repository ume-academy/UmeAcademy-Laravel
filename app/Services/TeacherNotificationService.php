<?php

namespace App\Services;

use App\Models\TeacherNotification;
use App\Traits\ValidationTrait;

class TeacherNotificationService
{
    use ValidationTrait;

    public function create($data) {
        return TeacherNotification::create($data);
    }

    public function getAllByTeacher($perPage) {
        $teacher = $this->validateTeacher();

        return TeacherNotification::where('teacher_id', $teacher->id)->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function updateNotifyTeacher($id) {
        $teacher = $this->validateTeacher();
        $notify = TeacherNotification::findOrFail($id);

        if($notify->teacher_id != $teacher->id) {
            throw new \Exception('Có lỗi xảy ra');
        }

        $notify->is_read = 1;
        return $notify->save();
    }
}
