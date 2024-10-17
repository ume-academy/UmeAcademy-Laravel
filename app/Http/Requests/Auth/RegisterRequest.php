<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
            'first_name' => 'required|string|max:32',
            'last_name' => 'required|string|max:32',
            'email' => 'required|email|unique:users|max:64',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'Trường họ là bắt buộc.',
            'first_name.max' => 'Trường họ tối đa :max.',
            'last_name.required' => 'Trường tên là bắt buộc.',
            'last_name.max' => 'Trường tên tối đa :max.',
            'email.required' => 'Trường email là bắt buộc',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã được sử dụng. Hãy thử lại với email khác!',
            'password.required' => 'Mật khẩu là bắt buộc.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}
