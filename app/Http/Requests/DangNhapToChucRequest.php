<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DangNhapToChucRequest extends FormRequest
{
     public function authorize(): bool
    {
        return true;
    }

    // Các quy tắc validate
    public function rules(): array
    {
        return [
            'email'    => 'required',
            'password' => 'required',
        ];
    }

    // Custom messages cho validation
    public function messages(): array
    {
        return [
            'email.required'    => 'Email không được để trống.',
            'password.required' => 'Mật khẩu không được để trống.',
        ];
    }
}
