<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'duration',
        'is_preview',
        'lesson_id'
    ];

    public function lesson() {
        return $this->belongsTo(Lesson::class);
    }
}
