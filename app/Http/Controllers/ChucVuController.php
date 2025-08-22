<?php

namespace App\Http\Controllers;

use App\Models\ChiTietCapQuyen;
use App\Models\ChucVu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ChucVuController extends Controller
{
    public function getDataChucVu()
    {
        $data = ChucVu::select()->get();
        return response()->json([
            'data' => $data,
        ]);
    }

    public function createDataChucVu(Request $request)
    {
        $id_chuc_nang = 4;
        $user = $this->isUserAdmin();
        $checkQuyen = ChiTietCapQuyen::where('id_chuc_vu', $user->id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
        if (!$checkQuyen) {
            return response()->json([
                'message'  =>   'Bạn chưa được cấp quyền này',
                'status'   =>   false,
            ]);
        }

        ChucVu::create([
            'ten_chuc_vu' => $request->ten_chuc_vu,
        ]);
        return response()->json([
            'message'  =>   'Thêm chức vụ thành công',
            'status'   =>   true
        ]);
    }

    public function deleteChucVu($id)
    {
        $id_chuc_nang = 4;
        $user = $this->isUserAdmin();
        $checkQuyen = ChiTietCapQuyen::where('id_chuc_vu', $user->id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
        if (!$checkQuyen) {
            return response()->json([
                'message'  =>   'Bạn chưa được cấp quyền này',
                'status'   =>   false,
            ]);
        }
        $ten_chuc_vu = ChucVu::where('id', $id)->first();

        if ($ten_chuc_vu) {
            $ten_chuc_vu->delete();

            return response()->json([
                'status' => true,
                'message' => "Xóa thành công"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Có lỗi xảy ra"
            ]);
        }
    }
    public function UpateChucVu(Request $request)
    {
        $id_chuc_nang = 4;
        $user = $this->isUserAdmin();
        $checkQuyen = ChiTietCapQuyen::where('id_chuc_vu', $user->id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
        if (!$checkQuyen) {
            return response()->json([
                'message'  =>   'Bạn chưa được cấp quyền này',
                'status'   =>   false,
            ]);
        }
        $ten_chuc_vu = ChucVu::where('id', $request->id)->first();
        if ($ten_chuc_vu) {
            $ten_chuc_vu->update([
                'ten_chuc_vu'             => $request->ten_chuc_vu,

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

}
