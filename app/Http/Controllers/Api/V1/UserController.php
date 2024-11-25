<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;


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
        $file->save($path);

        // Trả về đường dẫn lưu ảnh
        return 'avatars/' . $filename;
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            $userId = Auth::id();// Lấy ID của người dùng hiện tại
            if (!$userId) {
                return response()->json(['error' => 'Người dùng chưa đăng nhập.'], 401);
            }
            // Lấy thông tin cần cập nhật từ request
            $data = $request->only(['fullname','email', 'bio']);
            // Xử lý upload avatar nếu có
            if ($request->hasFile('avatar')) {
                $avatarPath = $this->uploadAvatar($request->file('avatar'));
                $data['avatar'] = $avatarPath;
            }
            // Cập nhật thông tin người dùng
            $user = $this->userService->updateUser($userId, $data);
            // Trả về phản hồi với thông tin người dùng được cập nhật
            return UserResource::make($user);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
