<?php

namespace App\Http\Controllers;

use Shuchkin\SimpleXLSX;
use Illuminate\Http\Request;
use App\Models\ThongTinUpload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ThongTinUploadController extends Controller
{
    public function import(Request $request)
    {
        try {
            $to_chuc = $this->isUserToChucCapChungChi();
            if ($to_chuc) {
                $file = $request->file('file');

                if (!$file) {
                    return response()->json(['error' => 'Không có tệp tải lên'], 400);
                }
                if ($xlsx = SimpleXLSX::parse($file->getPathname())) {
                    foreach ($xlsx->rows() as $index => $row) {
                        if ($index === 0) continue; // Bỏ qua tiêu đề

                        ThongTinUpload::create([
                            'id_to_chuc' => $to_chuc->id,
                            'so_hieu_chung_chi' => $row[1],
                            'hinh_anh' => $row[2],
                            'khoa_hoc' => $row[3],
                            'trinh_do' => $row[4],
                            'ngay_cap' => $row[5],
                            'ket_qua' => $row[6],
                            'ho_ten' => $row[7],
                            'so_cccd' => $row[8],
                            'sdt' => $row[9],
                            'email' => $row[10],
                        ]);
                    }

                    return response()->json([
                        'status' => true,
                        'message' => 'Tải lên thành công'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Có lỗi xảy ra'
                    ]);
                }
            }
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra'
            ]);
        }
    }

    public function getData()
    {
        $to_chuc = $this->isUserToChucCapChungChi();
        if ($to_chuc) {
            $data = ThongTinUpload::where('id_to_chuc', $to_chuc->id)
                ->get();
            return response()->json(['data'=>$data]);
        }
    }
}
