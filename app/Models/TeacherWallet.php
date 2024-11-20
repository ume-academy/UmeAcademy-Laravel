<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'available_balance',
        'temporary_balance',
        'total_earnings',
        'teacher_id'
    ];

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function teacherWalletTransactions() {
        return $this->hasMany(TeacherWalletTransaction::class);
    }
}
