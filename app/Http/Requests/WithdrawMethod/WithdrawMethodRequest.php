<?php

namespace App\Http\Requests\WithdrawMethod;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'name_bank' => 'required|max:255',
            'name_account' => 'required|string|max:255',
            'number_account' => 'required|numeric|digits_between:10,20',
        ];
    }

    public function messages()
    {
        return [
            'name_bank.required' => 'Tên ngân hàng không được để trống.',
            'name_bank.max' => 'Tên ngân hàng không được vượt quá 255 ký tự.',

            'name_account.required' => 'Tên tài khoản không được để trống.',
            'name_account.string' => 'Tên tài khoản phải là chuỗi ký tự.',
            'name_account.max' => 'Tên tài khoản không được vượt quá 255 ký tự.',
            
            'number_account.required' => 'Số tài khoản không được để trống.',
            'number_account.numeric' => 'Số tài khoản phải là số.',
            'number_account.digits_between' => 'Số tài khoản phải có từ 10 đến 20 chữ số.',
        ];
    }

    
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 500));
    } 
}
