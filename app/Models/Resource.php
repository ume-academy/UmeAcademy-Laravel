<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'lesson_id'];

    public function lesson() {
        return $this->belongsTo(Lesson::class);
    }
}
