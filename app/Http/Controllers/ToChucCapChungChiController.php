<?php

namespace App\Http\Controllers;

use App\Http\Requests\DangKyToChucRequest;
use App\Http\Requests\DangNhapToChucRequest;
use App\Mail\ToChucQuenMatKhau;
use App\Models\ChiTietCapQuyen;
use App\Models\ToChucCapChungChi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
class ToChucCapChungChiController extends Controller
{
    public function dangKy(DangKyToChucRequest $request)
    {
        ToChucCapChungChi::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'ten_to_chuc' => $request->ten_to_chuc,
            'hotline' => $request->hotline,
            'dia_chi' => $request->dia_chi,
            'ho_ten_nguoi_dai_dien' => $request->ho_ten_nguoi_dai_dien,
            'so_cccd' => $request->so_cccd,
            'sdt_nguoi_dai_dien' => $request->sdt_nguoi_dai_dien,
            'email_nguoi_dai_dien' => $request->email_nguoi_dai_dien,
            'is_duyet' => 0,

        ]);
        return response()->json([
            'message'  =>   'Vui lòng đợi duyệt',
            'status'   =>   true
        ]);
    }
    public function dangNhap(DangNhapToChucRequest $request)
    {
        $check = Auth::guard('to_chuc_cap_chung_chi')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);
        if ($check) {
            $user = Auth::guard('to_chuc_cap_chung_chi')->user();
            if ($user->is_duyet == 1) {
                return response()->json([
                    'message'  =>   'Đăng Nhập Thành Công.',
                    'status'   =>   true,
                    'chia_khoa' =>   $user->createToken('ma_so_chia_khoa_to_chuc')->plainTextToken,
                    'ten_to_chuc' =>   $user->ten_to_chuc,
                ]);
            } else if ($user->is_duyet == 0) {
                return response()->json([
                    'message'  =>   'Vui lòng đợi duyệt',
                    'status'   =>   false
                ]);
            } else if($user->is_duyet == 2) {
                return response()->json([
                    'message'  =>   'Tài khoản đã bị khóa',
                    'status'   =>   false,
                ]);
            }
        }else{
            return response()->json([
                'message'  =>   'Sai tài khoản hoặc mật khẩu',
                'status'   =>   false,
            ]);
        }
    }
    public function getData()
    {
        $data = ToChucCapChungChi::select()->get();
        return response()->json([
            'data' => $data,
        ]);
    }
    public function kiemTraChiaKhoa()
    {
        $check = $this->isUserToChucCapChungChi();
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
        $data = Auth::guard('sanctum')->user();
        return response()->json([
            'data' => $data,
        ]);
    }
    public function chonAvt(Request $request)
    {
        $check = $this->isUserToChucCapChungChi();
        if ($check) {
            ToChucCapChungChi::where('id', $check->id)->update([
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
    public function updateProfile(Request $request)
    {
        $check = Auth::guard('sanctum')->user();
        if ($check) {
            ToChucCapChungChi::where('id', $check->id)->update([
                'email' => $request->email,
                'ten_to_chuc' => $request->ten_to_chuc,
                'hotline' => $request->hotline,
                'dia_chi' => $request->dia_chi,
                'ho_ten_nguoi_dai_dien' => $request->ho_ten_nguoi_dai_dien,
                'so_cccd' => $request->so_cccd,
                'sdt_nguoi_dai_dien' => $request->sdt_nguoi_dai_dien,
                'email_nguoi_dai_dien' => $request->email_nguoi_dai_dien,
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
            $doi = ToChucCapChungChi::where('id', $check->id)->first();
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
        $check = ToChucCapChungChi::where('email', $request->email)->first();
        if ($check) {
            $check->hash_reset = Str::uuid();
            $check->save();
            Mail::to($request->email)->send(new ToChucQuenMatKhau($check->hash_reset, $check->ten_to_chuc));
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
        $check = ToChucCapChungChi::where('hash_reset', $hash_reset)->first();
        if ($check) {
            $check->password = bcrypt($request->password);
            $check->hash_reset = null;
            $check->save();
            return response()->json([
                'status' => true,
                'message' => "Đổi mật khẩu thành công"
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
        $tochuc = ToChucCapChungChi::where('id', $request->id)->first();

        if ($tochuc) {
            if ($tochuc->is_duyet == 0) {
                $tochuc->is_duyet = 1;
            } else {
                $tochuc->is_duyet = 0;
            }
            $tochuc->save();

            return response()->json([
                'status'    =>   true,
                'message'   =>   'Đổi trạng thái thành công',
            ]);
        } else {
            return response()->json([
                'status'    =>   false,
                'message'   =>   'Có lỗi xảy ra'
            ]);
        }
    }
    public function getDataTen()
    {
        $data = ToChucCapChungChi::select('id','ten_to_chuc')->get();
        return response()->json([
            'data' => $data,
        ]);
    }
    public function getTKTimKiem(Request $request)
    {
        $tim_kiem = "%" . $request->tim . "%";

        $data = ToChucCapChungChi::where('ten_to_chuc', 'like', $tim_kiem)
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
