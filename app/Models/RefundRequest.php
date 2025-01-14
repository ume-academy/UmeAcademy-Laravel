<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefundRequest extends Model
{
    use HasFactory, SoftDeletes;

    const REJECT = 0;
    const SUCCESS = 1;
    const PENDING = 2;

    protected $fillable = [
        'status',
        'transaction_code',
        'refund_reason'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_code', 'transaction_code');
    }
}
