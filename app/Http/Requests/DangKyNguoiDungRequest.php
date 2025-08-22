<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DangKyNguoiDungRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [

             'ho_ten'    => 'required',
             'email'     => 'required|email|unique:hoc_viens,email,' . $this->id,
             'password'  => 'required',
             'ngay_sinh' => 'required|date|before:today',
             'gioi_tinh' => 'required',
             'so_cccd'   => 'required',
             'sdt'       => 'required|digits:10',
             'dia_chi'   => 'required',

        ];
    }
    public function messages(): array
{
    return [
        'email.required'    => 'Vui lòng nhập email',
        'email.email'       => 'Email không đúng định dạng.',
        'email.unique'      => 'Email đã tồn tại.',

        'password.required' => 'Vui lòng nhập mật khẩu',

        'ho_ten.required'   => 'Vui lòng nhập họ tên',

        'ngay_sinh.required'=> 'Vui lòng nhập ngày sinh',
        'ngay_sinh.before'  => 'Ngày sinh phải nhỏ hơn ngày hiện tại.',

        'gioi_tinh.required'=> 'Vui lòng chọn giới tính',

        'so_cccd.required'  => 'Vui lòng nhập số CCCD',

        'sdt.required'      => 'Vui lòng nhập số điện thoại',
        'sdt.digits'        => 'Số điện thoại phải đúng 10 chữ số.',

        'dia_chi.required'  => 'Vui lòng nhập địa chỉ',
    ];
}

}
