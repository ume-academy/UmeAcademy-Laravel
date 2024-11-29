<?php

namespace App\Http\Requests\Resource;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreResourceRequest extends FormRequest
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
            'name' => [
                'required',
                'file',
                'max:10240',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Resource là trường bắt buộc.',
            'name.file' => 'Resource phải là một file hợp lệ.',
            'name.max' => 'Dung lượng resource không được vượt quá 10MB.',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 500));
    } 
}
