<?php

namespace App\Services;

use App\Exceptions\Teacher\AlreadyTeacherException;
use App\Models\TeacherWalletTransaction;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use App\Repositories\Interfaces\TeacherWalletRepositoryInterface;
use App\Repositories\Interfaces\TeacherWalletTransactionRepositoryInterface;
use App\Traits\ValidationTrait;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
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
            'revenue' => $wallet->total_earnings,
            'total_student' => $courses->sum('total_student'),
            'total_rating' => $courses->count() > 0 ? round($courses->sum('rating') / $courses->count(), 2) : 5
        ];

        return $data;
    }

    public function getRevenue($data) {
        $teacher = $this->validateTeacher();
    
        // Xử lý ngày bắt đầu và ngày kết thúc
        $startDate = $data['start_date'] ?? now()->startOfYear()->toDateString(); 
        $endDate = $data['end_date'] ?? now()->addDay()->toDateString();

        $transactions = $this->teacherWalletTransactionRepo->filterRevenue($teacher->id, $startDate, $endDate);

        // Chuyển thành dạng ['date' => 'total_revenue']
        $transactionData = $transactions->pluck('total_revenue', 'date')->toArray();

        // Tạo danh sách tất cả các ngày trong khoảng thời gian
        $allDates = $this->getAllDates($startDate, $endDate);

        $result = collect($allDates)->map(function ($date) use ($transactionData) {
            return [
                'date' => $date,
                'revenue' => $transactionData[$date] ?? 0,
            ];
        });
    
        return $result->toArray();
    }

    public function getStatisticOfTeacher($id) {
        $user = JWTAuth::parseToken()->authenticate();

        if(!$user || !$user->hasRole('admin')) {
            throw new AuthorizationException('Unauthorized');
        }
        
        $wallet =  $this->teacherWalletRepo->getByTeacherId($id);
        $courses = $this->courseRepo->getCourseOfTeacher($id);
        
        $data = [
            'revenue' => $wallet->total_earnings,
            'total_student' => $courses->sum('total_student'),
            'total_rating' => $courses->count() > 0 ? round($courses->sum('rating') / $courses->count(), 2) : 5
        ];

        return $data;
    }
    
    public function getWalletTransactionByTeacher($id, $perPage) {
        $user = JWTAuth::parseToken()->authenticate();

        if(!$user || !$user->hasRole('admin')) {
            throw new AuthorizationException('Unauthorized');
        }

        $wallet =  $this->teacherWalletRepo->getByTeacherId($id);
        
        return $this->teacherWalletTransactionRepo->getByWalletId($wallet->id, $perPage);
    }

    public function getCoursesByTeacher($id, $perPage) {
        $user = JWTAuth::parseToken()->authenticate();

        if(!$user || !$user->hasRole('admin')) {
            throw new AuthorizationException('Unauthorized');
        }

        return $this->courseRepo->getByTeacher($id, $perPage);
    }

    private function getAllDates($startDate, $endDate) {
        $allDates = [];
        $currentDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
    
        while ($currentDate < $endDate) {
            $allDates[] = $currentDate->toDateString();
            $currentDate->addDay();
        }
    
        return $allDates;
    }
}
