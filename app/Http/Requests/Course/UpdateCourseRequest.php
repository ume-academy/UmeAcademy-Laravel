<?php

namespace App\Http\Requests\Course;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCourseRequest extends FormRequest
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
            'name' => 'required|string',
            'summary' => 'required|string',
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|exists:categories,id',
            'level_id' => 'required|exists:levels,id',
        ];
    }
    
    public function messages()
    {
        return [
            'name.required' => 'Tên không được trống.',
            'name.string' => 'Tên phải là một chuỗi.',
            
            'summary.required' => 'Tóm tắt không được trống.',
            'summary.string' => 'Tóm tắt phải là một chuỗi.',
            
            'thumbnail.image' => 'Hình thu nhỏ phải là một ảnh.',
            'thumbnail.mimes' => 'Hình thu nhỏ phải có định dạng jpeg, png, jpg, gif hoặc svg.',
            'thumbnail.max' => 'Hình thu nhỏ không được vượt quá 2MB.',
            
            'category_id.required' => 'Danh mục không được trống.',
            'category_id.exists' => 'Danh mục không tồn tại.',
            
            'level_id.required' => 'Cấp độ không được trống.',
            'level_id.exists' => 'Cấp độ không tồn tại.',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 500));
    }
}
