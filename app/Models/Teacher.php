<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bio',
        'rating',
        'user_id'
    ];

    public function teacherWallet() {
        return $this->hasOne(TeacherWallet::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

}
