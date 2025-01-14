<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentWalletTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'type',
        'balance_tracking',
        'note',
        'student_wallet_id'
    ];

    public function studentWallet() {
        return $this->belongsTo(StudentWallet::class);
    }
}
