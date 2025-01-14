<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentWallet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'balance',
        'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function studentWalletTransactions() {
        return $this->hasMany(StudentWalletTransaction::class);
    }
}
