<?php

namespace App\Http\Controllers;

use App\Models\ViNft;
use Illuminate\Http\Request;

class ViNftController extends Controller
{
    public function getDataViHV()
    {
        $hoc_vien = $this->isUserHocVien();
        if ($hoc_vien) {
            $data = ViNft::where('id_hoc_vien', $hoc_vien->id)
                ->where('tinh_trang', 1)
                ->get();
            return response()->json([$data]);
        }
    }
}
