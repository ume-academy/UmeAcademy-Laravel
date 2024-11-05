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
}
