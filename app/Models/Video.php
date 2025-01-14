<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'duration',
        'is_preview',
        'lesson_id'
    ];

    public function lesson() {
        return $this->belongsTo(Lesson::class);
    }

    protected $casts = [
        'is_preview' => 'boolean',
    ];
}
