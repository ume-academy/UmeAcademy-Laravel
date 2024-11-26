<?php

namespace App\Http\Requests\WithdrawMethod;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawMethodRequest extends FormRequest
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
            // 'name_bank' => 'required|max:255',
            'name_account' => 'nullable|string|max:255',
            // 'branch' => 'nullable|string|max:255', // Chi nhánh: không bắt buộc, chuỗi
            // 'number_account' => 'required|number|digits_between:8,16', // Số tài khoản: bắt buộc, độ dài từ 8-16 ký tự
            // //
        ];
    }
    public function messages(): array
    {
        return [
            'name_bank.required' => 'Tên là bắt buộc.',            
        ];
    }
    
}
