<?php

namespace App\Repositories;

use App\Models\RefundRequest;
use App\Repositories\Interfaces\RefundRepositoryInterface;

class RefundRepository implements RefundRepositoryInterface
{
    public function getAll($perPage, $status = null) {
        $query = RefundRequest::orderBy('created_at', 'desc'); // Sắp xếp theo ngày tạo
        
        // Lọc theo trạng thái nếu có (chuyển trạng thái chữ thành số)
        if ($status !== null) {
            switch ($status) {
                case 'reject':
                    $query->where('status', RefundRequest::REJECT);
                    break;
                case 'pending':
                    $query->where('status', RefundRequest::PENDING);
                    break;
                case 'success':
                    $query->where('status', RefundRequest::SUCCESS);
                    break;
                default:
                    break;
            }
        }
    
        return $query->paginate($perPage);
    }

    public function find(int $id) {
        return RefundRequest::findOrFail($id);
    }

    public function updateStatus(int $id, $status) {
        $request = $this->find($id);

        $request->status = $status;
        $request->save();
        
        return $request;
    }
}
