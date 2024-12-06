<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'positions',
        'chapter_id'
    ];

    public function chapter() {
        return $this->belongsTo(Chapter::class);
    }

    public function video() {
        return $this->hasOne(Video::class);
    }

    public function lessonCompleted() {
        return $this->belongsToMany(User::class, 'lesson_completeds', 'lesson_id', 'user_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'lesson_completeds')
            ->withPivot('created_at');
    }
}
