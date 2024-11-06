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
            'code' => 'required|string|max:10|unique:vouchers,code',
            'quantity' => 'required|integer|min:1',
            'discount' => 'required|numeric|between:0,100',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Mã giảm giá không được để trống.',
            'code.string' => 'Mã giảm giá phải là một chuỗi ký tự.',
            'code.max' => 'Mã giảm giá không được vượt quá 20 ký tự.',
            'code.unique' => 'Mã giảm giá này đã tồn tại.',

            'quantity.required' => 'Số lượng không được để trống.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 1.',

            'discount.required' => 'Mức giảm giá không được để trống.',
            'discount.numeric' => 'Mức giảm giá phải là số.',
            'discount.between' => 'Mức giảm giá phải nằm trong khoảng từ 0 đến 100.',

            'start_date.required' => 'Ngày bắt đầu không được để trống.',
            'start_date.date' => 'Ngày bắt đầu phải là ngày hợp lệ.',
            'start_date.before_or_equal' => 'Ngày bắt đầu phải trước hoặc bằng ngày kết thúc.',

            'end_date.required' => 'Ngày kết thúc không được để trống.',
            'end_date.date' => 'Ngày kết thúc phải là ngày hợp lệ.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'message' => 'Thêm mới voucher không thành công',
            'errors' => $validator->errors()
        ], 500));
    } 
}
