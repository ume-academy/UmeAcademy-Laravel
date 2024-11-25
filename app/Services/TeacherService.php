<?php

namespace App\Services;

use App\Exceptions\Teacher\AlreadyTeacherException;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use App\Repositories\Interfaces\TeacherWalletRepositoryInterface;
use App\Repositories\Interfaces\TeacherWalletTransactionRepositoryInterface;
use App\Traits\ValidationTrait;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class TeacherService
{
    use ValidationTrait;

    public function __construct(
        private TeacherRepositoryInterface $teacherRepo,
        private TeacherWalletRepositoryInterface $teacherWalletRepo,
        private TeacherWalletTransactionRepositoryInterface $teacherWalletTransactionRepo,
        private CourseRepositoryInterface $courseRepo
    ){}

    public function registerTeacher()
    {
        $user = JWTAuth::parseToken()->authenticate();
    
        if ($user->teacher()->exists()) {
            throw new AlreadyTeacherException();
        }
    
        DB::beginTransaction();
        
        try {
            // Thêm dữ liệu bảng teachers
            $dataTeacher = ['user_id' => $user->id];
            $teacher = $this->teacherRepo->create($dataTeacher);
    
            // Thêm dữ liệu bảng teacher_wallets
            $dataTeacherWallet = ['teacher_id' => $teacher->id];
            $this->teacherWalletRepo->create($dataTeacherWallet);
    
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function checkTeacher() {
        $user = JWTAuth::parseToken()->authenticate();
    
        if ($user->teacher()->exists()) {
            return true;
        }

        return false;
    }

    public function getInfoTeacher($id) {
        return $this->teacherRepo->getById($id);
    }

    public function getWalletBalance() {
        $teacher = $this->validateTeacher();
        $wallet =  $this->teacherWalletRepo->getByTeacherId($teacher->id);
        return $wallet->available_balance;
    }

    public function getWalletTransaction($perPage) {
        $teacher = $this->validateTeacher();
        $wallet =  $this->teacherWalletRepo->getByTeacherId($teacher->id);
        
        return $this->teacherWalletTransactionRepo->getByWalletId($wallet->id, $perPage);
    }

    public function getStatistic() {
        $teacher = $this->validateTeacher();

        $wallet =  $this->teacherWalletRepo->getByTeacherId($teacher->id);
        $courses = $this->courseRepo->getCourseOfTeacher($teacher->id);

        $data = [
            'revenue' => $wallet->available_balance,
            'total_student' => $courses->sum('total_student'),
            'total_rating' => $courses->sum('rating') / $courses->count()
        ];

        return $data;
    }
}
