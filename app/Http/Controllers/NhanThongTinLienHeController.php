<?php

namespace App\Http\Controllers;

use App\Models\NhanThongTinLienHe;
use Illuminate\Http\Request;

class NhanThongTinLienHeController extends Controller
{
    public function tao(Request $request)
    {
        $tao = NhanThongTinLienHe::create([
            'ho_ten' => $request->ho_ten,
            'sdt' => $request->sdt,
            'tieu_de' => $request->tieu_de,
            'noi_dung' => $request->noi_dung,
        ]);
        if ($tao) {
            return response()->json([
                'message'  =>   'Gửi thành công',
                'status'   =>   true
            ]);
        } else {
            return response()->json([
                'message'  =>   'Có lỗi xảy ra',
                'status'   =>   false
            ]);
        }
    }
    public function xem()
    {
        $check = $this->isUserAdmin();
        if ($check) {
            $data = NhanThongTinLienHe::all();
            return response()->json([
                'data' => $data,
            ]);
        }
    }
}
