<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'thumbnail',
        'summary',
        'video',
        'price',
        'duration',
        'total_lesson',
        'total_chapter',
        'status',
        'category_id',
        'level_id',
        'teacher_id'
    ];

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function level() {
        return $this->belongsTo(Level::class);
    }

    public function chapters() {
        return $this->hasMany(Chapter::class);
    }

    public function lessons() {
        return $this->hasManyThrough(Lesson::class, Chapter::class);
    }

    // Tính tổng số chương của khóa học
    public function getTotalChapterAttribute() {
        return $this->chapters()->count();
    }

    // Tính tổng số bài học của khóa học
    public function getTotalLessonAttribute() {
        return $this->lessons()->count();
    }

    // Tính tổng thời gian của khóa học
    public function getDurationAttribute() {
        return $this->lessons()->with('video')->get()->sum(function($lesson) {
            return $lesson->video ? $lesson->video->duration : 0;
        });
    }
}
