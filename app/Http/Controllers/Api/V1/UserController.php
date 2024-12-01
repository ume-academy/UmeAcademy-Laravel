<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Requests\User\ChagePasswordRequest;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService,
    ){}

    public function getListUser(Request $req) {
        try {
            $perPage = $req->input('per_page', 10);

            $users = $this->userService->getListUser($perPage);

            return UserResource::collection($users);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateProfile(UpdateProfileRequest $req) {
        try {
            $data = $req->only(['fullname', 'avatar', 'bio']);
            
            if($req->hasFile('avatar')) {
                $data['avatar'] = $req->file('avatar');
            }

            $user = $this->userService->updateProfile($data);

            return new UserResource($user);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function lock($id) {
        try {
            $user = $this->userService->lock($id);

            return new UserResource($user);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function unlock($id) {
        try {
            $user = $this->userService->unlock($id);

            return new UserResource($user);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getUser($id) {
        try {
            $user = $this->userService->getUser($id);

            return new UserResource($user);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // changePassword
    public function changePassword(ChagePasswordRequest $request)
    {
        try {
            // Lấy thông tin user từ JWT token
            $user = JWTAuth::parseToken()->authenticate();

            $this->userService->changePassword($user->id, $request->old_password, $request->new_password);
            return response()->json(['message' => 'Mật khẩu đã được thay đổi thành công.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    
}
