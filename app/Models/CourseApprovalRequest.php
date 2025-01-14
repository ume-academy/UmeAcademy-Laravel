<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseApprovalRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'status',
        'teacher_id',
        'course_id'
    ];

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
}
