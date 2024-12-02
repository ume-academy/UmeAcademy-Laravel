<?php

namespace App\Repositories;

use App\Models\RefundRequest;
use App\Repositories\Interfaces\RefundRepositoryInterface;

class RefundRepository implements RefundRepositoryInterface
{
    public function getAll($perPage) {
        return RefundRequest::paginate($perPage);
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
