<?php

namespace App\Services;

use App\Exceptions\Teacher\AlreadyTeacherException;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use App\Repositories\Interfaces\TeacherWalletRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class TeacherService
{
    public function __construct(
        private TeacherRepositoryInterface $teacherRepo,
        private TeacherWalletRepositoryInterface $teacherWalletRepo
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
}
