<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_code',
        'origin_price',
        'discount_price',
        'revenue_teacher',
        'fee_platform',
        'user_id',
        'course_id',
        'payment_method_id',
        'status'
    ];
}
