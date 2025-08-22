<?php

namespace App\Http\Controllers;

use App\Models\ChungChi;
use App\Models\DonHang;
use App\Models\GiaoDich;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GiaoDichController extends Controller
{
    public function index()
    {
        Log::info('GiaoDichController@index da chay');

        $client = new Client();
        $payload = [
            'USERNAME' => env('MB_USERNAME'),
            'PASSWORD' => env('MB_PASSWORD'),
            'NUMBER_MB' => env('MB_NUMBER'),
            "DAY_BEGIN" => Carbon::today()->format('d/m/Y'),
            "DAY_END" => Carbon::today()->format('d/m/Y'),
        ];

        try {
            $response = $client->post('https://api-mb.dzmid.io.vn/api/transactions', [
                'json' => $payload
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['data']['transactionHistoryList']) && is_array($data['data']['transactionHistoryList'])) {
                $duLieu = $data['data']['transactionHistoryList'];

                Log::info('Dữ liệu giao dịch trả về:', $duLieu);

                foreach ($duLieu as $value) {
                    $giaoDich = GiaoDich::where('pos', $value['pos'])
                        ->where('creditAmount', $value['creditAmount'])
                        ->where('description', $value['description'])
                        ->first();

                    if (!$giaoDich) {
                        GiaoDich::create([
                            'creditAmount' => $value['creditAmount'],
                            'description' => $value['description'],
                            'pos' => $value['pos'],
                        ]);

                        // Xử lý liên kết đơn hàng
                        $description = $value['description'];

                        if (preg_match('/HDBD(\d+)/', $description, $matches)) {
                            $maDonHang = $matches[0];


                            $donHang = DonHang::where('ma_don_hang', $maDonHang)
                                ->where('tong_tien_thanh_toan', '<=', $value['creditAmount'])
                                ->first();

                            if ($donHang) {
                                $donHang->is_thanh_toan = 1;
                                $donHang->save();
                                Log::info('Cập nhật đơn hàng thành công: ' . $maDonHang);

                                $chung_chis = ChungChi::join('chi_tiet_don_hangs', 'chi_tiet_don_hangs.id_chung_chi', '=', 'chung_chis.id')
                                    ->where('chi_tiet_don_hangs.id_don_hang', $donHang->id)
                                    ->select('chung_chis.*') // Chỉ lấy bảng chính
                                    ->get();

                                foreach ($chung_chis as $chung_chi) {
                                    $chung_chi->tinh_trang = 1;
                                    $chung_chi->save();
                                }
                            } else {
                                Log::warning('Không tìm thấy đơn hàng tương ứng cho mã: ' . $maDonHang);
                            }
                        } else {
                            Log::warning('Không tìm thấy mã đơn hàng trong description: ' . $description);
                        }
                    }
                }
            } else {
                Log::error('Dữ liệu trả về không hợp lệ: ' . json_encode($data));
            }
        } catch (Exception $e) {
            Log::error('Lỗi khi lấy giao dịch: ' . $e->getMessage());
        }
    }
}
