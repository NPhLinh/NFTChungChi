<?php

namespace App\Http\Controllers;

use App\Http\Requests\DangKyNguoiDungRequest;
use App\Http\Requests\DangNhapNguoiDungRequest;
use App\Mail\HocVienQuenMatKhau;
use App\Models\ChiTietCapQuyen;
use App\Models\HocVien;
use App\Models\ViNft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PhpParser\Node\Expr\FuncCall;

class HocVienController extends Controller
{
    public function dangKy(DangKyNguoiDungRequest $request)
    {
        HocVien::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'ho_ten' => $request->ho_ten,
            'ngay_sinh' => $request->ngay_sinh,
            'gioi_tinh' => $request->gioi_tinh,
            'so_cccd' => $request->so_cccd,
            'sdt' => $request->sdt,
            'dia_chi' => $request->dia_chi,
            'is_duyet' => 0,
        ]);
        return response()->json([
            'message'  =>   'Vui lòng đợi duyệt',
            'status'   =>   true
        ]);
    }
    public function dangNhap(DangNhapNguoiDungRequest $request)
    {
        $check = Auth::guard('hoc_vien')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);
        if ($check) {
            $user = Auth::guard('hoc_vien')->user();
            if ($user->is_duyet == 1) {
                return response()->json([
                    'message'  =>   'Đăng nhập thành công',
                    'status'   =>   true,
                    'chia_khoa' =>   $user->createToken('ma_so_chia_khoa_hoc_vien')->plainTextToken,
                    'ten_hoc_vien' =>   $user->ho_ten,
                    'hinh_anh_hoc_vien' =>   $user->hinh_anh

                ]);
            } else if ($user->is_duyet == 0) {
                return response()->json([
                    'message'  =>   'Vui lòng đợi duyệt',
                    'status'   =>   false
                ]);
            } else if ($user->is_duyet == 2) {
                return response()->json([
                    'message'  =>   'Tài khoản đã bị khóa',
                    'status'   =>   false,
                ]);
            }
        } else {
            return response()->json([
                'message'  =>   'Sai tài khoản hoặc mật khẩu',
                'status'   =>   false,
            ]);
        }
    }
    public function getData()
    {
        $data = HocVien::get();
        return response()->json([
            'data' => $data,
        ]);
    }
    public function kiemTraChiaKhoa()
    {
        $check = $this->isUserHocVien();
        if ($check) {
            return response()->json([
                'status'   =>   true,
                'message'  =>   '',
            ]);
        } else {
            return response()->json([
                'status'   =>   false,
                'message'  =>   'Yêu cầu đăng nhập',
            ]);
        }
    }
    public function dangXuat()
    {
        $check = Auth::guard('sanctum')->user();
        if ($check) {
            DB::table('personal_access_tokens')
                ->where('id', $check->currentAccessToken()->id)->delete();

            return response()->json([
                'status' => true,
                'message' => "Đã đăng xuất thiết bị này thành công"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Vui lòng đăng nhập"
            ]);
        }
    }
    public function dangXuatAll()
    {
        $check = Auth::guard('sanctum')->user();
        if ($check) {
            $ds_token = $check->tokens;
            foreach ($ds_token as $k => $v) {
                $v->delete();
            }

            return response()->json([
                'status' => true,
                'message' => "Đã đăng xuất tất cả thiết bị thành công"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Vui lòng đăng nhập"
            ]);
        }
    }
    public function Profile()
    {
        $data = $this->isUserHocVien();
        return response()->json([
            'data' => $data,
        ]);
    }
    public function updateProfile(Request $request)
    {
        $check = $this->isUserHocVien();
        if ($check) {
            HocVien::where('id', $check->id)->update([
                'email' => $request->email,
                'ho_ten' => $request->ho_ten,
                'ngay_sinh' => $request->ngay_sinh,
                'gioi_tinh' => $request->gioi_tinh,
                'so_cccd' => $request->so_cccd,
                'sdt' => $request->sdt,
                'dia_chi' => $request->dia_chi,
            ]);
            return response()->json([
                'status' => true,
                'message' => "Cập nhật thành công"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Có lỗi xảy ra"
            ]);
        }
    }
    public function chonAvt(Request $request)
    {
        $check = $this->isUserHocVien();
        if ($check) {
            HocVien::where('id', $check->id)->update([
                'hinh_anh' => $request->hinh_anh,
            ]);
            return response()->json([
                'status' => true,
                'message' => "Cập nhật thành công"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Có lỗi xảy ra"
            ]);
        }
    }
    public function updateMatKhau(Request $request)
    {
        $check = $this->isUserHocVien();
        if ($check) {
            $doi = HocVien::where('id', $check->id)->first();
            if (Hash::check($request->password, $doi->password)) {
                $doi->update([
                    'password' => bcrypt($request->update_password),
                ]);
                return response()->json([
                    'status' => true,
                    'message' => "Đổi mật khẩu thành công"
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Mật khẩu cũ sai"
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => "Có lỗi xảy ra"
            ]);
        }
    }
    public function actionQuenmatKhau(Request $request)
    {
        $check = HocVien::where('email', $request->email)->first();
        if ($check) {
            $check->hash_reset = Str::uuid();
            $check->save();
            Mail::to($request->email)->send(new HocVienQuenMatKhau($check->hash_reset, $check->ho_ten));
            return response()->json([
                'status' => true,
                'message' => "Kiểm tra email"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Có lỗi xảy ra"
            ]);
        }
    }
    public function actionLayLaiMatKhau($hash_reset, Request $request)
    {
        $check = HocVien::where('hash_reset', $hash_reset)->first();
        if ($check) {
            $check->password = bcrypt($request->password);
            $check->hash_reset = null;
            $check->save();
            return response()->json([
                'status' => true,
                'message' => "Đổi mật khẩu thành công, chuyển về trang đăng nhập sau 10s"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Có lỗi xảy ra"
            ]);
        }
    }

    public function doiTrangThaiHocVien(Request $request)
    {
         $id_chuc_nang = 2;
        $user = $this->isUserAdmin();
        $checkQuyen = ChiTietCapQuyen::where('id_chuc_vu', $user->id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
        if (!$checkQuyen) {
            return response()->json([
                'message'  =>   'Bạn chưa được cấp quyền này',
                'status'   =>   false,
            ]);
        }

        $hocvien = HocVien::where('id', $request->id)->first();

        if ($hocvien) {
            if ($hocvien->is_duyet == 0) {
                $hocvien->is_duyet = 1;
            } else if ($hocvien->is_duyet == 1) {
                $hocvien->is_duyet = 2;
            } else if ($hocvien->is_duyet == 2) {
                $hocvien->is_duyet = 1;
            }

            $hocvien->save();

            return response()->json([
                'status'    =>   true,
                'message'   =>   'Đã đổi trạng thái thành công ',
            ]);
        } else {
            return response()->json([
                'status'    =>   false,
                'message'   =>   'Có lỗi xảy ra'
            ]);
        }
    }

    public function capNhatDiaChiVi(Request $request)
    {
        $this->isUserHocVien();

        $user = Auth::guard('sanctum')->user();
        $check = ViNft::where('id_hoc_vien', $user->id)->first();
        if ($check) {
            $check->dia_chi_vi = $request->dia_chi_vi;
            $check->save();
        } else {
            ViNft::create([
                'id_hoc_vien' => $user->id,
                'dia_chi_vi'  => $request->dia_chi_vi,
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => "Cập nhật địa chỉ ví thành công"
        ]);
    }

    public function getDataDiaChiVi()
    {
        $this->isUserHocVien();
        $user = Auth::guard('sanctum')->user();
        $check = ViNft::where('id_hoc_vien', $user->id)->first();
        if ($check) {
            return response()->json([
                'dia_chi_vi' => $check->dia_chi_vi,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'dia_chi_vi' => null,
            ]);
        }
    }
    public function getTKTimKiem(Request $request)
    {
        $tim_kiem = "%" . $request->tim . "%";

        $data = HocVien::where('ho_ten', 'like', $tim_kiem)
            ->orWhere('email', 'like', $tim_kiem)
            ->get();
        if ($data->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy kết quả'
            ]);
        }
        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }
}
