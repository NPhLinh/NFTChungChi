<?php

namespace App\Http\Controllers;

use App\Models\DonHang;
use App\Models\LichSuGiaoDich;
use Illuminate\Http\Request;

class LichSuGiaoDichController extends Controller
{

    public function lsGiaoDichNhan(){
        $user = $this->isUserAdmin();
        $data = DonHang::get();
        return response()->json([
            'data' => $data,
        ]);
    }
     public function lsGiaoDichGui(){
        $user = $this->isUserAdmin();
        $data = DonHang::get();
        return response()->json([
            'data' => $data,
        ]);
    }
}
