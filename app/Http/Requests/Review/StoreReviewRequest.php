<?php

namespace App\Http\Requests\Review;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreReviewRequest extends FormRequest
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
            'content' => 'required|string|max:1000',
            'rating' => 'required|integer|between:1,5',
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Nội dung đánh giá không được để trống.',
            'content.string' => 'Nội dung đánh giá phải là chuỗi.',
            'content.max' => 'Nội dung đánh giá không được vượt quá 1000 ký tự.',
            
            'rating.required' => 'Bạn phải cung cấp điểm đánh giá.',
            'rating.integer' => 'Điểm đánh giá phải là một số nguyên.',
            'rating.between' => 'Điểm đánh giá phải nằm trong khoảng từ 1 đến 5.',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 500));
    }
}
