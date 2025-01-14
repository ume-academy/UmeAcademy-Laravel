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
            'fullname' => 'required|regex:/^[\pL\s]+$/u|max:64',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'min:8',
                'max:32',
                'regex:/[A-Za-z0-9]/', 
                'regex:/[!@#$%^&*]/', 
            ],
        ];
    }

    public function messages()
    {
        return [
            'fullname.required' => 'Tên đầy đủ là bắt buộc.',
            'fullname.regex' => 'Tên đầy đủ chỉ được chứa các ký tự chữ cái và dấu cách.',
            'fullname.max' => 'Tên đầy đủ dài tối đa :max.',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email này đã được sử dụng. Hãy thử lại với email khác!',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
            'password.max' => 'Mật khẩu không được vượt quá :max ký tự.',
            'password.regex' => 'Mật khẩu bao gồm a-z, A-Z, 0-9 và phải chứa ít nhất một ký tự đặc biệt.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()->toArray(),
        ], 422));
    }
}
