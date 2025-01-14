<?php

namespace App\Services;

use App\Models\UserNotification;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserNotificationService
{
    public function create($data) {
        return UserNotification::create($data);
    }

    public function getAllByUser($perPage) {
        $user = JWTAuth::parseToken()->authenticate();

        return UserNotification::where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function updateNotifyUser($id) {
        $user = JWTAuth::parseToken()->authenticate();
        $notify = UserNotification::findOrFail($id);

        if($notify->user_id != $user->id) {
            throw new \Exception('Có lỗi xảy ra');
        }

        $notify->is_read = 1;
        return $notify->save();
    }
}
