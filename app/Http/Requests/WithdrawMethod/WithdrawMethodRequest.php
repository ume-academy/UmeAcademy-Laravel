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
            'name_bank' => 'required',
            'name_account' => 'required|string',
            // 'branch' => 'required|string',
            'number_account' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'name_bank.required' => 'Tên ngân hàng là bắt buộc.',

            'name_account.required' => 'Tên chủ tài khoản là bắt buộc.',
            'name_account.string' => 'Tên chủ tài khoản phải là một chuỗi ký tự.',

            // 'branch.required' => 'Chi nhánh là bắt buộc.',
            // 'branch.string' => 'Chi nhánh phải là một chuỗi ký tự.',

            'number_account.required' => 'Số tài khoản là bắt buộc.',
            'number_account.numeric' => 'Số tài khoản phải là một số.',
        ];
        
    }
    
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 500));
    } 
}
