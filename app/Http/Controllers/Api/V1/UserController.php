<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;




class UserController extends Controller
{
    public function __construct(
        private UserService $userService,
    ) {
    }
    protected function uploadAvatar($file)
    {
        // Kiểm tra file có hợp lệ không
        if (!$file->isValid() || !in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg'])) {
            throw new \Exception('File không hợp lệ. Chỉ chấp nhận định dạng JPG, JPEG, hoặc PNG.');
        }
        $imagePath = $file->getPathname();  // Lấy đường dẫn tạm thời của ảnh
        list($width, $height) = getimagesize($imagePath);  // Lấy chiều rộng và chiều cao của ảnh
        // Kiểm tra kích thước ảnh (width và height phải nhỏ hơn 800px)
        if ($width > 800 || $height > 800) {
            throw new \Exception('Kích thước ảnh không được vượt quá 800x800 pixel.');
        }
        // Sinh tên file
        $filename = time() . '.' . $file->getClientOriginalExtension();
        // Lưu ảnh vào thư mục public/avatars
        $path = public_path('avatars/' . $filename);
        $file->move($path,$filename);
        return 'avatars/' . $filename;
    }
    public function updateProfile(UpdateProfileRequest $request, $userId)
    {
        try {           
            $data = $request->only(['fullname', 'email', 'bio']);
            // Xử lý upload avatar nếu có
            if ($request->hasFile('avatar')) {
                $avatarPath = $this->uploadAvatar($request->file('avatar'));
                $data['avatar'] = $avatarPath;
            }
            $user = $this->userService->updateUser($userId, $data);
            // Trả về phản hồi với thông tin người dùng được cập nhật
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thông tin thành công.',
                'data' => new UserResource($user),
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
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
