<?php

namespace App\Repositories;

use App\Models\TeacherWalletTransaction;
use App\Repositories\Interfaces\TeacherWalletTransactionRepositoryInterface;

class TeacherWalletTransactionRepository implements TeacherWalletTransactionRepositoryInterface
{
    public function create(array $data) {
        return TeacherWalletTransaction::create($data);
    }

    public function getByWalletId(int $id, $perPage) {
        return TeacherWalletTransaction::where('teacher_wallet_id', $id)->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function filterRevenue(int $id, $startDate, $endDate) {
        return TeacherWalletTransaction::query()
                ->where('teacher_wallet_id', $id) // Lấy theo giao dịch của ví
                ->whereIn('type', ['available_receive_money', 'temporary_receive_money'])
                ->whereBetween('created_at', [$startDate, $endDate]) // lọc theo ngày
                ->selectRaw('DATE(created_at) as date, SUM(balance_tracking) as total_revenue')
                ->groupBy('date') // Nhóm theo ngày
                ->orderBy('date') // Sắp xếp theo ngày tăng dần
                ->get();
    }
}
