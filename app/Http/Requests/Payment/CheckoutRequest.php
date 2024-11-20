<?php

namespace App\Http\Requests\Payment;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CheckoutRequest extends FormRequest
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
            'origin_price' => 'required|numeric|min:0',
            'course_id' => 'required|exists:courses,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'voucher_id' => 'exists:vouchers,id'
        ];
    }

    public function messages()
    {
        return [
            'origin_price.required' => 'Giá gốc là bắt buộc.', 
            'origin_price.numeric' => 'Giá gốc phải là một số.',
            'origin_price.min' => 'Giá gốc không được nhỏ hơn 0.', 

            'course_id.required' => 'Khóa học là bắt buộc.',
            'course_id.exists' => 'Khóa học không tồn tại.', 

            'payment_method_id.required' => 'Phương thức thanh toán là bắt buộc.',
            'payment_method_id.exists' => 'Phương thức thanh toán không hợp lệ.',

            'voucher_id.exists' => 'Voucher không hợp lệ.',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 500));
    } 
}
