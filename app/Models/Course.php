<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'thumbnail',
        'summary',
        'video',
        'price',
        'duration',
        'total_lesson',
        'total_chapter',
        'rating',
        'status',
        'category_id',
        'level_id',
        'teacher_id'
    ];

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function level() {
        return $this->belongsTo(Level::class);
    }

    public function chapters() {
        return $this->hasMany(Chapter::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function lessons() {
        return $this->hasManyThrough(Lesson::class, Chapter::class);
    }

    public function wishList()
    {
        return $this->belongsToMany(User::class, 'wishlists', 'course_id', 'user_id');
    }

    public function courseEnrolled()
    {
        return $this->belongsToMany(User::class, 'course_enrolleds', 'course_id', 'user_id');
    }

    // Tính tổng số chương của khóa học
    public function getTotalChapterAttribute() {
        return $this->chapters()->count();
    }

    // Tính tổng số bài học của khóa học
    public function getTotalLessonAttribute() {
        return $this->lessons()->count();
    }

    // Tính tổng số học sinh
    public function getTotalStudentAttribute()
    {
        return $this->courseEnrolled()->count();
    }

    // Tính tổng thời gian của khóa học
    public function getDurationAttribute() {
        return $this->lessons()->with('video')->get()->sum(function($lesson) {
            return $lesson->video ? $lesson->video->duration : 0;
        });
    }

    // Tính rating khóa học
    public function getRatingAttribute()
    {
        $totalRatings = $this->reviews()->count();
        $sumRatings = $this->reviews()->sum('rating');

        return $totalRatings > 0 ? round($sumRatings / $totalRatings, 1) : 5;
    }

    // Phương thức kiểm tra nếu người dùng đã yêu thích khóa học
    public function checkWishlist($userId)
    {
        return $this->wishlist()->where('user_id', $userId)->exists();
    }

    // Phương thức kiểm tra nếu người dùng đã đăng ký khóa học
    public function checkEnrolled($userId)
    {
        return $this->courseEnrolled()->where('user_id', $userId)->exists();
    }
}
