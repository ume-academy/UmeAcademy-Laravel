<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherWalletTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'type',
        'balance_tracking',
        'note',
        'teacher_wallet_id'
    ];

    public function teacherWallet() {
        return $this->belongsTo(TeacherWallet::class);
    }
}
