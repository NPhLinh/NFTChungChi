<?php

namespace App\Http\Controllers;

use App\Models\ChiTietDonHang;
use App\Models\ChungChi;
use App\Models\HocVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChiTietDonHangController extends Controller
{
    public function themVaoThanhToan(Request $request)
    {
        $hoc_vien = $this->isUserHocVien();
        if (!$hoc_vien) {
            return response()->json([
                'status' => false,
                'message' => 'Bạn chưa đăng nhập'
            ]);
        }

        $chung_chi = ChungChi::where('id', $request->id_chung_chi)
            ->whereNull('token')
            ->where('id_hoc_vien', $hoc_vien->id)
            ->first();

        if (!$chung_chi) {
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra'
            ]);
        }

        $da_co = ChiTietDonHang::where('id_chung_chi', $chung_chi->id)
            ->first();
        if ($da_co) {
            return response()->json([
                'status' => false,
                'message' => 'Chứng chỉ này đã có trong giỏ hàng'
            ]);
        }

        ChiTietDonHang::create([
            'id_chung_chi'  => $chung_chi->id,
            'id_hoc_vien'   => $hoc_vien->id,
            'so_tien'       => $chung_chi->so_tien,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Thêm vào thanh toán thành công'
        ]);
    }
    public function getData()
    {
        $hoc_vien = $this->isUserHocVien();
        if ($hoc_vien) {
            $data = ChiTietDonHang::join('chung_chis', 'chung_chis.id', 'chi_tiet_don_hangs.id_chung_chi')
                ->where('chi_tiet_don_hangs.id_hoc_vien', $hoc_vien->id)
                ->whereNull('chung_chis.token')
                ->whereNull('chi_tiet_don_hangs.id_don_hang')
                ->join('hoc_viens', 'hoc_viens.id', 'chung_chis.id_hoc_vien')
                ->select('chi_tiet_don_hangs.*', 'chung_chis.so_hieu_chung_chi', 'chung_chis.id_to_chuc', 'chung_chis.so_tien', 'chung_chis.hinh_anh', 'chung_chis.khoa_hoc', 'chung_chis.trinh_do', 'chung_chis.ngay_cap', 'chung_chis.ket_qua', 'chung_chis.tinh_trang', 'chung_chis.ghi_chu','hoc_viens.ho_ten','hoc_viens.ngay_sinh', 'hoc_viens.so_cccd')
                ->get();
            return response()->json([
                'data' => $data,
            ]);
        }
    }
    public function xoaDonChiTiet(Request $request)
    {
        $data   =   ChiTietDonHang::where('id', $request->id)->first();
        if ($data) {
            $data->delete();
            return response()->json([
                'status'    =>   true,
                'message'   =>   'Xóa Thành Công'
            ]);
        } else {
            return response()->json([
                'status'    =>   false,
                'message'   =>   'Có lỗi xảy ra'
            ]);
        }
    }
}
