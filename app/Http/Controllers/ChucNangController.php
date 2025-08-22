<?php

namespace App\Http\Controllers;

use App\Models\ChiTietCapQuyen;
use App\Models\ChucNang;
use Illuminate\Http\Request;

class ChucNangController extends Controller
{
    public function getDataChucNang()
    {
        $user = $this->isUserAdmin();

        $data = ChucNang::select()->get();
        return response()->json([
            'data' => $data,
        ]);
    }
}
