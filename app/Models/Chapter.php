<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chapter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'course_id'];

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function lessons() {
        return $this->hasMany(Lesson::class);
    }

    public function completedLessonsCount(int $userId): int
    {
        return $this->lessons()->whereHas('lessonCompleted', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->count();
    }
}
