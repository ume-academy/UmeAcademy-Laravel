<?php

namespace App\Services;

use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\StudentWalletRepositoryInterface;
use App\Repositories\Interfaces\StudentWalletTransactionRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class StudentService
{
    public function __construct(
        private StudentWalletRepositoryInterface $studentWalletRepo,
        private StudentWalletTransactionRepositoryInterface $studentWalletTransactionRepo,
        private CourseRepositoryInterface $courseRepo,
        private UserRepositoryInterface $userRepo
    ){}

    public function getWalletBalance() {
        $user = JWTAuth::parseToken()->authenticate();

        $wallet =  $this->studentWalletRepo->getByStudentId($user->id);

        if(!$wallet) {
            throw new \Exception('Tài khoản chưa có ví.');
        }

        return $wallet->balance;
    }

    public function getWalletTransaction($perPage) {
        $user = JWTAuth::parseToken()->authenticate();
        $wallet =  $this->studentWalletRepo->getByStudentId($user->id);
        
        if(!$wallet) {
            throw new \Exception('Tài khoản chưa có ví.');
        }

        return $this->studentWalletTransactionRepo->getByWalletId($wallet->id, $perPage);
    }

    public function getWalletTransactionByStudent($id, $perPage) {
        $user = JWTAuth::parseToken()->authenticate();

        if(!$user || !$user->hasRole('admin')) {
            throw new AuthorizationException('Unauthorized');
        }

        $wallet =  $this->studentWalletRepo->getByStudentId($id);
        
        if(!$wallet) {
            throw new \Exception('Tài khoản chưa có ví.');
        }

        return $this->studentWalletTransactionRepo->getByWalletId($wallet->id, $perPage);
    }

    public function getPurchasedCoursesByStudent($id, $perPage) {
        $user = JWTAuth::parseToken()->authenticate();

        if(!$user || !$user->hasRole('admin')) {
            throw new AuthorizationException('Unauthorized');
        }

        $student = $this->userRepo->findById($id);

        return $this->courseRepo->getCourseOfStudent($student, $perPage);
    }
}
