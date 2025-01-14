<?php

namespace App\Http\Requests\Voucher;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreVoucherRequest extends FormRequest
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
            'code' => 'required|max:10|unique:vouchers,code', 
            'quantity' => 'required|integer|min:1',  
            'discount' => 'required|integer|between:1,100',   
            'start_date' => 'required|date|after_or_equal:today|before:end_date',
            'end_date' => 'required|date|after:start_date',
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'Mã giảm giá là bắt buộc.',
            'code.max' => 'Mã giảm giá không được vượt quá 10 ký tự.',
            'code.unique' => 'Mã giảm giá đã tồn tại.',

            'quantity.required' => 'Số lượng là bắt buộc.',
            'quantity.integer' => 'Số lượng phải là một số nguyên.',
            'quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 1.',

            'discount.required' => 'Giảm giá là bắt buộc.',
            'discount.integer' => 'Giảm giá phải là một số nguyên.',
            'discount.between' => 'Giảm giá phải trong khoảng từ 1 đến 100.',

            'start_date.required' => 'Ngày bắt đầu là bắt buộc.',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải là hôm nay trở đi.',
            'start_date.before' => 'Ngày bắt đầu phải trước ngày kết thúc.',
            
            'end_date.required' => 'Ngày kết thúc là bắt buộc.',
            'end_date.date' => 'Ngày kết thúc phải là ngày hợp lệ.',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'message' => 'Thêm mới voucher không thành công',
            'errors' => $validator->errors()
        ], 500));
    } 
}
