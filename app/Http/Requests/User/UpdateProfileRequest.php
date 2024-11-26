<?php

namespace App\Http\Requests\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
class updateProfileRequest extends FormRequest
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
            'fullname' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048|dimensions:max_width=800,max_height=800', // Max 2MB
            'bio' => 'nullable',
        ];
    }
    public function messages()
    {
        return [
            'fullname.required' => 'Tên là bắt buộc.',
            'fullname.string' => 'Tên chỉ bao gồm chữ',
            'fullname.max' => 'Tên độ dài không quá 255 ký tự',
            'avatar.image' => 'Avatar phải là image',
            'avatar.mimes' => 'Avatar phải thuộc định dạng jpg,png,jpeg ',
            'avatar.max' => 'Max 2MB',
        ];
    }
}
