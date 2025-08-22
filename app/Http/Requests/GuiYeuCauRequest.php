<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuiYeuCauRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'so_hieu_chung_chi'    => 'required',
            'id_to_chuc'           => 'required',
        ];
    }
    
    public function messages(): array
    {
        return [
            'so_hieu_chung_chi.required'    => 'Vui lòng nhập số hiệu chứng chỉ.',
            'id_to_chuc.required'           => 'Vui lòng chọn tổ chức.',
        ];
    }
}
