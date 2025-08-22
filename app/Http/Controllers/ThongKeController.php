<?php

namespace App\Http\Controllers;

use App\Models\ChiTietCapQuyen;
use App\Models\ChungChi;
use App\Models\DonHang;
use App\Models\ThongKe;
use Illuminate\Http\Request;

class ThongKeController extends Controller
{
    public function getThongKeDoanhThu()
    {
        $data = DonHang::where('is_thanh_toan',1)
        ->get();
        return response()->json([
            'data' => $data,
        ]);
    }
    public function getThongKeNFTDaCap()
    {
        $data = ChungChi::where('tinh_trang',2)
        ->get();
        return response()->json([
            'data' => $data,
        ]);
    }
}
