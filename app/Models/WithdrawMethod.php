<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WithdrawMethod extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name_bank',
        'name_account',
        'number_account',
        'branch',
        'min_withdraw',
        'teacher_id',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
        
}
