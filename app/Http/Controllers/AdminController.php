<?php

namespace App\Http\Controllers;

use App\Http\Requests\DangKyNhanVienRequest;
use App\Http\Requests\DangNhapAdmimRequest;
use App\Models\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\AdminQuenMatKhau;
use App\Models\ChiTietCapQuyen;
use App\Models\HocVien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function getData()
    {
        $data = Admin::join('chuc_vus', 'admins.id_chuc_vu', 'chuc_vus.id')
            ->select('admins.*', 'chuc_vus.ten_chuc_vu')
            ->get();
        return response()->json([
            'data' => $data,
        ]);
    }
    public function dangKy(DangKyNhanVienRequest $request)
    {
        $id_chuc_nang = 1;
        $user = $this->isUserAdmin();
        $checkQuyen = ChiTietCapQuyen::where('id_chuc_vu', $user->id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
        if (!$checkQuyen) {
            return response()->json([
                'message'  =>   'Bạn chưa được cấp quyền này',
                'status'   =>   false,
            ]);
        }

        Admin::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'ho_ten' => $request->ho_ten,
            'ngay_sinh' => $request->ngay_sinh,
            'gioi_tinh' => $request->gioi_tinh,
            'so_cccd' => $request->so_cccd,
            'sdt' => $request->sdt,
            'dia_chi' => $request->dia_chi,
            'id_chuc_vu' => $request->id_chuc_vu,
            'is_duyet' => 1,
        ]);
        return response()->json([
            'message'  =>   'Thêm tài khoản thành công',
            'status'   =>   true
        ]);
    }
    public function dangNhap(DangNhapAdmimRequest $request)
    {
        $check = Auth::guard('admin')->attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        if ($check) {
            $user = Auth::guard('admin')->user();

            if ($user->is_duyet == 1) {
                return response()->json([
                    'message'  =>   'Đăng nhập thành công.',
                    'status'   =>   true,
                    'chia_khoa' =>   $user->createToken('ma_so_chia_khoa_admin')->plainTextToken,
                    'ten_admin' =>   $user->ho_ten
                ]);
            } else if ($user->is_duyet == 2) {
                return response()->json([
                    'message'  =>   'Tài khoản đã bị khóa',
                    'status'   =>   false,
                ]);
            }
        } else {
            return response()->json([
                'message'  =>   'Sai thông tin đăng nhập',
                'status'   =>   false,
            ]);
        }

        return response()->json([
            'message' => 'Sai Thông Tin Đăng Nhập.',
            'status'  => false,
        ]);
    }

    public function kiemTraChiaKhoa()
    {
        $check = $this->isUserAdmin();
        if ($check) {
            return response()->json([
                'status'   =>   true,
                'message'  =>   'aaa',
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
        $check = $this->isUserAdmin();
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
        $check = $this->isUserAdmin();
        if ($check) {
            $ds_token = $check->tokens;
            foreach ($ds_token as $k => $v) {
                $v->delete();
            }

            return response()->json([
                'status' => true,
                'message' => "Đã đăng xuất tất cả thiết bị này thành công"
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
        $check = $this->isUserAdmin();
        $data = Admin::where('admins.id', $check->id)
            ->join('chuc_vus', 'admins.id_chuc_vu', 'chuc_vus.id')
            ->select('admins.*', 'chuc_vus.ten_chuc_vu')
            ->first();
        return response()->json([
            'data' => $data,
        ]);
    }
    public function updateProfile(Request $request)
    {
        $check = Auth::guard('sanctum')->user();

        if ($check) {
            Admin::where('id', $check->id)->update([
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
    public function updateMatKhau(Request $request)
    {
        $check = Auth::guard('sanctum')->user();
        if ($check) {
            $doi = Admin::where('id', $check->id)->first();
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
        $check = Admin::where('email', $request->email)->first();
        if ($check) {
            $check->hash_reset = Str::uuid();
            $check->save();
            Mail::to($request->email)->send(new AdminQuenMatKhau($check->hash_reset, $check->ho_ten));
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
        $check = Admin::where('hash_reset', $hash_reset)->first();
        if ($check) {
            $check->password = bcrypt($request->password);
            $check->hash_reset = null;
            $check->save();
            return response()->json([
                'status' => true,
                'message' => "Mật khẩu mới đã được cập nhật"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Có lỗi xảy ra"
            ]);
        }
    }
    public function doiTrangThai(Request $request)
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

        $admin = Admin::where('id', $request->id)->first();

        if ($admin) {
            if ($admin->is_duyet == 0) {
                $admin->is_duyet = 1;
            } else {
                $admin->is_duyet = 0;
            }
            $admin->save();

            return response()->json([
                'status'    =>   true,
                'message'   =>   'Đã đổi trạng thái thành công',
            ]);
        } else {
            return response()->json([
                'status'    =>   false,
                'message'   =>   'Có lỗi xảy ra'
            ]);
        }
    }
    public function getTKTimKiem(Request $request)
    {
        $tim_kiem = "%" . $request->tim . "%";

        $data = Admin::join('chuc_vus', 'admins.id_chuc_vu', '=', 'chuc_vus.id')
            ->select('admins.*', 'chuc_vus.ten_chuc_vu')
            ->where('admins.ho_ten', 'like', $tim_kiem)
            ->orWhere('admins.email', 'like', $tim_kiem)
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
    public function updateChucVuNhanVien(Request $request)
    {
        $id_chuc_nang = 7;
        $user = $this->isUserAdmin();
        $checkQuyen = ChiTietCapQuyen::where('id_chuc_vu', $user->id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
        if (!$checkQuyen) {
            return response()->json([
                'message'  =>   'Bạn chưa được cấp quyền này',
                'status'   =>   false,
            ]);
        }
        $check = $this->isUserAdmin();
        if ($check) {
            $doi = Admin::where('id', $request->id)->first();
            if ($doi) {
                $doi->update([
                    'id_chuc_vu' =>  $request->id_chuc_vu,
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
        } else {
            return response()->json([
                'status' => false,
                'message' => "Có lỗi xảy ra"
            ]);
        }
    }
}
