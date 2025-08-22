<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuiYeuCauRequest;
use App\Models\HocVien;
use App\Models\ViNft;
use App\Models\YeuCauCap;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YeuCauCapController extends Controller
{
    public function guiYeuCauCap(GuiYeuCauRequest $request)
    {
        $check = $this->isUserHocVien();
        if ($check) {
            $vi_nft = ViNft::where('id_hoc_vien', $check->id)
                ->whereNotNull('dia_chi_vi')->first();
            if ($vi_nft) {
                $yeuCauCap = YeuCauCap::create([
                    'id_to_chuc'     => $request->id_to_chuc,
                    'id_hoc_vien' => $check->id,
                    'ho_ten' => $check->ho_ten,
                    'so_cccd' => $check->so_cccd,
                    'email' => $check->email,
                    'so_hieu_chung_chi' => $request->so_hieu_chung_chi,
                    'trang_thai' => 0,
                ]);
                return response()->json([
                    'status'    =>   true,
                    'message'   =>   'Yêu cầu thành công',
                ]);
            }else{
                return response()->json([
                    'status'    =>   false,
                    'message'   =>   'Bạn chưa có địa chỉ ví',
                ]);
            }
        } else {
            return response()->json([
                'status'    =>   false,
                'message'   =>   'Có lỗi xảy ra'
            ]);
        }
    }
    public function getData()
    {
        $check = Auth::guard('sanctum')->user();
        $hoc_vien = $this->isUserHocVien();
        $to_chuc = $this->isUserToChucCapChungChi();
        if ($check) {
            if ($hoc_vien) {
                $data = YeuCauCap::where('id_hoc_vien', $check->id)
                    ->join('to_chuc_cap_chung_chis', 'to_chuc_cap_chung_chis.id', 'yeu_cau_caps.id_to_chuc')
                    ->select('yeu_cau_caps.*', 'to_chuc_cap_chung_chis.ten_to_chuc',)
                    ->get();
            } else if ($to_chuc) {
                $data = YeuCauCap::where('id_to_chuc', $check->id)
                    ->where('yeu_cau_caps.trang_thai', 0)
                    ->select('yeu_cau_caps.*')
                    ->get();
            }
            return response()->json([
                'data' => $data
            ]);
        }
    }
    public function getDataTruyXuat($id)
    {
        $check = Auth::guard('sanctum')->user();
        $to_chuc = $this->isUserToChucCapChungChi();
        if ($check) {
            if ($to_chuc) {
                $data = YeuCauCap::join('to_chuc_cap_chung_chis', 'yeu_cau_caps.id_to_chuc', 'to_chuc_cap_chung_chis.id')
                    ->join('thong_tin_uploads', 'to_chuc_cap_chung_chis.id', 'thong_tin_uploads.id_to_chuc')
                    ->where('yeu_cau_caps.id_to_chuc', $check->id)
                    ->where('yeu_cau_caps.id', $id)
                    ->whereColumn('thong_tin_uploads.id_to_chuc', 'to_chuc_cap_chung_chis.id')
                    ->whereColumn('yeu_cau_caps.email', 'thong_tin_uploads.email')
                    ->whereColumn('yeu_cau_caps.so_hieu_chung_chi', 'thong_tin_uploads.so_hieu_chung_chi')
                    ->select('thong_tin_uploads.*')
                    ->first();
                if ($data) {
                    YeuCauCap::where('id', $id)->update([
                        'trang_thai' => 1,
                    ]);
                    return response()->json([
                        'data' => $data,
                        'status' => true,
                        'message' => 'Có thông tin'
                    ]);
                } else {
                    YeuCauCap::where('id', $id)->update([
                        'trang_thai' => 2, //sai tt
                    ]);
                    return response()->json([
                        'status' => false,
                        'message' => 'Không tìm được thông tin'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Có lỗi xảy ra'
                ]);
            }
        }
    }
}
