<?php

namespace App\Repositories;

use App\Models\CourseApprovalRequest;
use App\Repositories\Interfaces\CourseApprovalRepositoryInterface;

class CourseApprovalRepository implements CourseApprovalRepositoryInterface
{
    public function create(array $data) {
        return CourseApprovalRequest::create($data);
    }
}
