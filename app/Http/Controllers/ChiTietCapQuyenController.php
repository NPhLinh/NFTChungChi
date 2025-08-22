<?php

namespace App\Http\Controllers;

use App\Models\ChiTietCapQuyen;
use Illuminate\Http\Request;

class ChiTietCapQuyenController extends Controller
{
    public function loadchiTietChucNang($id_chuc_vu)
    {

        $data = ChiTietCapQuyen::where('id_chuc_vu', $id_chuc_vu)
            ->join('chuc_nangs', 'chuc_nangs.id', 'chi_tiet_cap_quyens.id_chuc_nang')
            ->select('chi_tiet_cap_quyens.*', 'chuc_nangs.ten_chuc_nang')
            ->get();

        return response()->json([
            'data'    => $data,
        ]);
    }
    public function store(Request $request)
    {
        $id_chuc_nang = 3;
        $user = $this->isUserAdmin();
        $checkQuyen = ChiTietCapQuyen::where('id_chuc_vu', $user->id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
        if (!$checkQuyen) {
            return response()->json([
                'message'  =>   'Bạn chưa được cấp quyền này',
                'status'   =>   false,
            ]);
        }

        $check = ChiTietCapQuyen::where('id_chuc_vu', $request->id_chuc_vu)
            ->where('id_chuc_nang', $request->id_chuc_nang)
            ->first();

        if ($check) {
            return response()->json([
                'status'    => false,
                'message'   => 'Quyền này đã được phân'
            ]);
        }
        ChiTietCapQuyen::create([
            'id_chuc_vu' => $request->id_chuc_vu,
            'id_chuc_nang' => $request->id_chuc_nang
        ]);

        return response()->json([
            'status'    => true,
            'message'   => 'Phân quyền thành công'
        ]);
    }
    public function destroy(Request $request)
    {
        $id_chuc_nang = 3;
        $user = $this->isUserAdmin();
        $checkQuyen = ChiTietCapQuyen::where('id_chuc_vu', $user->id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
        if (!$checkQuyen) {
            return response()->json([
                'message'  =>   'Bạn chưa được cấp quyền này',
                'status'   =>   false,
            ]);
        }

        ChiTietCapQuyen::find($request->id)->delete();

        return response()->json([
            'status'    => true,
            'message'   => 'Thu hồi quyền thành công'
        ]);
    }
}
