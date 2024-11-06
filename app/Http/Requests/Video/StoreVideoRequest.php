<?php

namespace App\Http\Requests\Video;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'file',
                'mimetypes:video/mp4,video/mpeg,video/avi',
                'max:10240',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Video là trường bắt buộc.',
            'name.file' => 'Video phải là một file hợp lệ.',
            'name.mimetypes' => 'Chỉ chấp nhận các định dạng video: mp4, mpeg, avi.',
            'name.max' => 'Dung lượng video không được vượt quá 10MB.',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'message' => 'Thêm mới video vào bài học không thành công',
            'errors' => $validator->errors()
        ], 500));
    } 
}
