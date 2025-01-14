<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Notifications\TeacherNotifyResource;
use App\Http\Resources\Notifications\UserNotifyResource;
use App\Services\TeacherNotificationService;
use App\Services\UserNotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        private TeacherNotificationService $teacherNotificationService,
        private UserNotificationService $userNotificationService,
    ){}

    public function getAllByUser(Request $req) {
        try {
            $perPage = $req->input('per_page');
            $notifications = $this->userNotificationService->getAllByUser($perPage);
            
            return UserNotifyResource::collection($notifications);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAllByTeacher(Request $req) {
        try {
            $perPage = $req->input('per_page');
            $notifications = $this->teacherNotificationService->getAllByTeacher($perPage);
            
            return TeacherNotifyResource::collection($notifications);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateNotifyUser($id) {
        try {
            $notify = $this->userNotificationService->updateNotifyUser($id);
            
            if($notify) {
                return response()->json(['data' => 'success']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateNotifyTeacher($id) {
        try {
            $notify = $this->teacherNotificationService->updateNotifyTeacher($id);
            
            if($notify) {
                return response()->json(['data' => 'success']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
