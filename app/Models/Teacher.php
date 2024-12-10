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
        'job_title',
        'facebook',
        'twitter',
        'linkedin',
        'youtube',
        'user_id'
    ];

    public function teacherWallet() {
        return $this->hasOne(TeacherWallet::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function courses() {
        return $this->hasMany(Course::class);
    }

    public function withdrawMethod() {
        return $this->hasOne(WithdrawMethod::class);
    }

    public function getRatingAttribute() {
        $totalRating = $this->getTotalCourseRatings();
        $totalReviews = $this->getTotalReviewCount();
    
        // Trả về trung bình rating hoặc giá trị mặc định
        return $totalReviews > 0 ? round($totalRating / $totalReviews, 2) : $this->attributes['rating'] ?? null;
    }
    
    // Tổng số sao của tất cả khóa học
    protected function getTotalCourseRatings() {
        return $this->courses()->with('reviews')->get()->pluck('reviews')->flatten()->sum('rating');
    }
    
    // Tổng số đánh giá của tất cả khóa học
    public function getTotalReviewCount() {
        return $this->courses()->withCount('reviews')->get()->sum('reviews_count');
    }
    
    
}
