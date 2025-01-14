<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'quantity',
        'discount',
        'used_count',
        'start_date',
        'end_date',
        'creator_type',
        'course_id',
        'teacher_id'
    ];

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function usages() {
        return $this->hasMany(VoucherUsage::class);
    }

    public function getUsedCountAttribute () {
        return $this->usages()->count();
    }
}
