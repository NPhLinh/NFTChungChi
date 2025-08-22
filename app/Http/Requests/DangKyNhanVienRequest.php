<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DangKyNhanVienRequest extends FormRequest
{
     public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [

             'ho_ten'    => 'required',
             'so_cccd'   => 'required',
             'sdt'       => 'required|digits:10',
             'id_chuc_vu'=> 'required',
             'gioi_tinh' => 'required',
             'email'     => 'required|email|unique:admins,email,' . $this->id,
             'password'  => 'required',
             'ngay_sinh' => 'required|before:today',
             'dia_chi'   => 'required',

        ];
    }
    public function messages(): array
{
    return [
        'ho_ten.required'       => 'Vui lòng nhập họ tên.',

        'so_cccd.required'      => 'Vui lòng nhập số CCCD.',

        'sdt.required'          => 'Vui lòng nhập số điện thoại.',
        'sdt.digits'            => 'Số điện thoại phải có đúng 10 chữ số.',

        'id_chuc_vu.required'   => 'Vui lòng chọn chức vụ.',

        'gioi_tinh.required'    => 'Vui lòng chọn giới tính.',

        'email.required'        => 'Vui lòng nhập email.',
        'email.email'           => 'Email không đúng định dạng.',
        'email.unique'          => 'Email đã tồn tại.',

        'password.required'     => 'Vui lòng nhập mật khẩu.',

        'ngay_sinh.required'    => 'Vui lòng nhập ngày sinh.',
        'ngay_sinh.before'      => 'Ngày sinh phải nhỏ ngày hiện tại.',

        'dia_chi.required'      => 'Vui lòng nhập địa chỉ.',
    ];
}


}
