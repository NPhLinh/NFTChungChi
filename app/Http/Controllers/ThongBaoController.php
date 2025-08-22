<?php

namespace App\Http\Controllers;

use App\Models\ChiTietCapQuyen;
use App\Models\HocVien;
use App\Models\ThongBao;
use App\Models\ThongBaoNguoiNhan;
use App\Models\ToChucCapChungChi;
use Illuminate\Http\Request;

class ThongBaoController extends Controller
{
    public function guiThongBao(Request $request)
    {
        $id_chuc_nang = 5;
        $user = $this->isUserAdmin();
        $checkQuyen = ChiTietCapQuyen::where('id_chuc_vu', $user->id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
        if (!$checkQuyen) {
            return response()->json([
                'message'  =>   'Bạn chưa được cấp quyền này',
                'status'   =>   false,
            ]);
        }
        try {
            $tb = ThongBao::create([
                'tieu_de' => $request->tieu_de,
                'noi_dung' => $request->noi_dung,
                'loai_nhan' => $request->loai_nhan,
                'doi_tuong' => $request->doi_tuong,
                'id_hoc_vien' => $request->id_hoc_vien,
                'id_to_chuc' => $request->id_to_chuc,
            ]);
            if ($tb) {
                $id_hv = HocVien::where('is_duyet', 1)->pluck('id')->toArray();
                $id_tc = ToChucCapChungChi::where('is_duyet', 1)->pluck('id')->toArray();

                switch ($tb->loai_nhan) {
                    case 0: // Gửi cho tất cả
                        foreach ($id_hv as $id) {
                            ThongBaoNguoiNhan::create([
                                'id_thong_bao' => $tb->id,
                                'id_hoc_vien' => $id,
                            ]);
                        }
                        foreach ($id_tc as $id) {
                            ThongBaoNguoiNhan::create([
                                'id_thong_bao' => $tb->id,
                                'id_to_chuc' => $id,
                            ]);
                        }
                        break;

                    case 1: // Gửi cho học viên
                        foreach ($id_hv as $id) {
                            ThongBaoNguoiNhan::create([
                                'id_thong_bao' => $tb->id,
                                'id_hoc_vien' => $id,
                            ]);
                        }
                        break;

                    case 2: // Gửi cho tổ chức
                        foreach ($id_tc as $id) {
                            ThongBaoNguoiNhan::create([
                                'id_thong_bao' => $tb->id,
                                'id_to_chuc' => $id,
                            ]);
                        }
                        break;

                    case 3: // Gửi đối tượng cụ thể
                        if ($tb->doi_tuong == 1) {
                            ThongBaoNguoiNhan::create([
                                'id_thong_bao' => $tb->id,
                                'id_hoc_vien' => $tb->id_hoc_vien,
                            ]);
                        } else if ($tb->doi_tuong == 2) {
                            ThongBaoNguoiNhan::create([
                                'id_thong_bao' => $tb->id,
                                'id_to_chuc' => $tb->id_to_chuc,
                            ]);
                        }
                        break;
                }

                return response()->json([
                    'message' => 'Thông báo đã được gửi thành công',
                    'status' => true
                ]);
            } else {
                return response()->json([
                    'message' => 'Có lỗi xảy ra',
                    'status' => false
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message'  =>   'Có lỗi xảy ra',
                'status'   =>   false
            ]);
        }
    }
    public function getData()
    {
        $data = ThongBao::leftJoin('hoc_viens', 'thong_baos.id_hoc_vien', '=', 'hoc_viens.id')
            ->leftJoin('to_chuc_cap_chung_chis', 'thong_baos.id_to_chuc', '=', 'to_chuc_cap_chung_chis.id')
            ->select(
                'thong_baos.*',
                'hoc_viens.email as email_hv',
                'to_chuc_cap_chung_chis.email as email_tc'
            )
            ->get();
        return response([
            'data' => $data,
        ]);
    }
}
