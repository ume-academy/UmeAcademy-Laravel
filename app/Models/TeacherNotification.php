<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherNotification extends Model
{
    use HasFactory;

    protected $fillable = ['message', 'is_read', 'teacher_id'];

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
}
