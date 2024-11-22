<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
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
}
