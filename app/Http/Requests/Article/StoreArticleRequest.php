<?php

namespace App\Http\Requests\Article;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreArticleRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:10'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'status' => ['required', 'in:draft,published']
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Tiêu đề bài viết là bắt buộc.',
            'title.string' => 'Tiêu đề phải là chuỗi ký tự.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',

            'content.required' => 'Nội dung bài viết là bắt buộc.',
            'content.string' => 'Nội dung bài viết phải là chuỗi ký tự.',
            'content.min' => 'Nội dung bài viết phải có ít nhất 10 ký tự.',

            'thumbnail.image' => 'Hình thu nhỏ phải là một tệp hình ảnh.',
            'thumbnail.mimes' => 'Hình thu nhỏ chỉ hỗ trợ các định dạng: jpeg, png, jpg, gif.',
            'thumbnail.max' => 'Hình thu nhỏ không được vượt quá 5MB.',

            'user_id.required' => 'Người dùng tạo bài viết là bắt buộc.',
            'user_id.exists' => 'Người dùng không hợp lệ hoặc không tồn tại.',

            'status.required' => 'Trạng thái bài viết là bắt buộc.',
            'status.in' => 'Trạng thái chỉ được phép là: draft, published.',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 500));
    } 
}
