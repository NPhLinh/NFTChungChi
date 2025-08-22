<?php

namespace App\Http\Controllers;

use App\Jobs\DeleteUnpaidOrder;
use App\Mail\HocVienThanhToan;
use App\Models\ChiTietDonHang;
use App\Models\DonHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class DonHangController extends Controller
{
    //trước khi bấm thanh toán thì phải thêm vào giỏ hàng/ chi tiết đơn hàng
    public function actionThanhToan(Request $request)
    {
        try {
            $hoc_vien = Auth::guard('sanctum')->user();
            if ($hoc_vien) {
                if (count($request->ds_chung_chi_thanh_toan) < 1) {
                    return response()->json([
                        'status' => false,
                        'message' => "Không có chứng chỉ nào cần thanh toán"
                    ]);
                } else {
                    $don_hang = DonHang::create([
                        'ma_don_hang' => '',
                        'tong_tien_thanh_toan' => 0,
                        'is_thanh_toan' => 0,
                        'ho_ten' => $hoc_vien->ho_ten,
                        'email' => $hoc_vien->email,
                        'ma_qr' => 0,
                        'id_hoc_vien' => $hoc_vien->id,
                    ]);
                    $ma_don_hang = "HDBD" . (100100 + $don_hang->id);
                    $tong_tien_thanh_toan = 0;
                    foreach ($request->ds_chung_chi_thanh_toan as $key => $value) {
                        $tong_tien_thanh_toan += $value['so_tien'];
                        ChiTietDonHang::where('id', $value['id'])->update([
                            'id_don_hang' => $don_hang->id,
                        ]);
                    };
                    $don_hang->ma_don_hang = $ma_don_hang;
                    $don_hang->tong_tien_thanh_toan = $tong_tien_thanh_toan;
                    $don_hang->save();

                    $chi_tiet_don_hang = ChiTietDonHang::where('id_don_hang', $don_hang->id)
                        ->join('chung_chis', 'chi_tiet_don_hangs.id_chung_chi', 'chung_chis.id')
                        ->get();

                    $qr_link   =   "https://img.vietqr.io/image/MB-0347341227-qr_only.png?amount=" . $tong_tien_thanh_toan . "&addInfo=" . $ma_don_hang;

                    DeleteUnpaidOrder::dispatch($don_hang->id)
                        ->delay(now()->addMinutes(6));

                    return response()->json([
                        'status' => true,
                        'message' => "Mời bạn thanh toán",
                        'data'    => [
                            'ma_don_hang' => $ma_don_hang,
                            'tong_tien_thanh_toan'   => $tong_tien_thanh_toan,
                            'qr_link'     => $qr_link,
                            'chi_tiet_don_hang' => $chi_tiet_don_hang,
                        ],

                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Có lỗi xảy ra"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => "Có lỗi xảy ra"
            ]);
        }
    }
    public function getHocVienLichSuGiaoDich(){
        $check = $this->isUserHocVien();
        if($check){
            $don_hang = DonHang::where('id_hoc_vien', $check->id)
            ->where('is_thanh_toan',1)
            ->get();
             return response()->json([
               'data'=> $don_hang
            ]);
        }
    }
}
