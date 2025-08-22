<?php

namespace App\Http\Controllers;

use App\Models\ThongBaoNguoiNhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ThongBaoNguoiNhanController extends Controller
{
    public function xemThongBao()
    {
        $check = Auth::guard('sanctum')->user();
        $hoc_vien = $this->isUserHocVien();
        $to_chuc = $this->isUserToChucCapChungChi();

        if ($hoc_vien) {
            $data = ThongBaoNguoiNhan::join('thong_baos', 'thong_bao_nguoi_nhans.id_thong_bao', 'thong_baos.id')
                ->where('thong_bao_nguoi_nhans.id_hoc_vien', $check->id)
                ->select('thong_bao_nguoi_nhans.*', 'thong_baos.tieu_de', 'thong_baos.noi_dung', 'thong_baos.created_at')
                ->get();
            return response()->json([
                'data' => $data,
            ]);
        } else if ($to_chuc) {
            $data = ThongBaoNguoiNhan::join('thong_baos', 'thong_bao_nguoi_nhans.id_thong_bao', 'thong_baos.id')
                ->where('thong_bao_nguoi_nhans.id_to_chuc', $check->id)
                ->select('thong_bao_nguoi_nhans.*', 'thong_baos.tieu_de', 'thong_baos.noi_dung', 'thong_baos.created_at')
                ->get();
            return response()->json([
                'data' => $data,
            ]);
        }
    }
    public function xemChiTietThongBao($id)
    {
        $hoc_vien = $this->isUserHocVien();
        $to_chuc = $this->isUserToChucCapChungChi();
        if ($hoc_vien) {
            $data = ThongBaoNguoiNhan::join('thong_baos', 'thong_bao_nguoi_nhans.id_thong_bao', 'thong_baos.id')
                ->where('thong_bao_nguoi_nhans.id', $id)
                ->where('thong_bao_nguoi_nhans.id_hoc_vien', $hoc_vien->id)
                ->select('thong_bao_nguoi_nhans.*', 'thong_baos.tieu_de', 'thong_baos.noi_dung', 'thong_baos.created_at')
                ->first();
            return response()->json([
                'data' => $data,
            ]);
        } else if ($to_chuc) {
            $data = ThongBaoNguoiNhan::join('thong_baos', 'thong_bao_nguoi_nhans.id_thong_bao', 'thong_baos.id')
                ->where('thong_bao_nguoi_nhans.id', $id)
                ->where('thong_bao_nguoi_nhans.id_to_chuc', $to_chuc->id)
                ->select('thong_bao_nguoi_nhans.*', 'thong_baos.tieu_de', 'thong_baos.noi_dung', 'thong_baos.created_at')
                ->first();
            return response()->json([
                'data' => $data,
            ]);
        }
    }
    public function xoaThongBao(Request $request)
    {
        $hoc_vien = $this->isUserHocVien();
        $to_chuc = $this->isUserToChucCapChungChi();

        try {
            if ($hoc_vien) {
                foreach ($request->ds_thong_bao_can_xoa as $id) {
                    ThongBaoNguoiNhan::where('id', $id)
                        ->where('id_hoc_vien', $hoc_vien->id)
                        ->delete();
                }
            } else if ($to_chuc) {
                foreach ($request->ds_thong_bao_can_xoa as $id) {
                    ThongBaoNguoiNhan::where('id', $id)->where('id_to_chuc', $to_chuc->id)
                        ->delete();
                }
            }
            return response()->json([
                'status'    => true,
                'message'   => 'Xóa thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'    => false,
                'message'   => 'Có lỗi xảy ra'
            ]);
        }
    }
}
