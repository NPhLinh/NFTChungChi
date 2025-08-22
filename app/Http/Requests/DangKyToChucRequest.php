<?php

namespace App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;

class DangKyToChucRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'                 => 'required|email',
            'password'              => 'required',
            'ten_to_chuc'           => 'required',
            'hotline'               => 'required',
            'dia_chi'               => 'required',
            'ho_ten_nguoi_dai_dien' => 'required',
            'so_cccd'               => 'required',
            'sdt_nguoi_dai_dien'    => 'required|digits:10',
            'email_nguoi_dai_dien'  => 'required|email',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $email = $this->input('email');
            $emailNguoiDaiDien = $this->input('email_nguoi_dai_dien');
            $id = $this->id;
            if ($email === $emailNguoiDaiDien) {
                $validator->errors()->add('email_nguoi_dai_dien', 'Email người đại diện không được trùng với email tổ chức.');
            }

            $emailExists = DB::table('to_chuc_cap_chung_chis')
                ->where(function ($query) use ($email) {
                    $query->where('email', $email)
                          ->orWhere('email_nguoi_dai_dien', $email);
                })
                ->where('id', '!=', $id)
                ->exists();

            if ($emailExists) {
                $validator->errors()->add('email', 'Email tổ chức đã tồn tại trong hệ thống.');
            }

            $emailNguoiDaiDienExists = DB::table('to_chuc_cap_chung_chis')
                ->where(function ($query) use ($emailNguoiDaiDien) {
                    $query->where('email', $emailNguoiDaiDien)
                          ->orWhere('email_nguoi_dai_dien', $emailNguoiDaiDien);
                })
                ->where('id', '!=', $id)
                ->exists();

            if ($emailNguoiDaiDienExists) {
                $validator->errors()->add('email_nguoi_dai_dien', 'Email người đại diện đã tồn tại trong hệ thống.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'email.required'                 => 'Vui lòng nhập email tổ chức.',
            'email.email'                    => 'Email tổ chức không đúng định dạng.',

            'password.required'              => 'Vui lòng nhập mật khẩu.',

            'ten_to_chuc.required'           => 'Vui lòng nhập tên tổ chức.',

            'hotline.required'               => 'Vui lòng nhập hotline.',

            'dia_chi.required'               => 'Vui lòng nhập địa chỉ.',

            'ho_ten_nguoi_dai_dien.required' => 'Vui lòng nhập họ tên người đại diện.',

            'so_cccd.required'               => 'Vui lòng nhập số CCCD.',

            'sdt_nguoi_dai_dien.required'    => 'Vui lòng nhập số điện thoại người đại diện.',
            'sdt_nguoi_dai_dien.digits'      => 'Số điện thoại người đại diện phải có đúng 10 chữ số.',

            'email_nguoi_dai_dien.required'  => 'Vui lòng nhập email người đại diện.',
            'email_nguoi_dai_dien.email'     => 'Email người đại diện không đúng định dạng.',
        ];
    }
}
