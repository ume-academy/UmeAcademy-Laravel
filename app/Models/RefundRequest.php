<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefundRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'money',
        'status',
        'user_id',
        'course_id',
        'teacher_id',
        'refund_reason'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
}
