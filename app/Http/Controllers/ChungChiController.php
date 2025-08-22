<?php

namespace App\Http\Controllers;

use App\Models\ChiTietCapQuyen;
use App\Models\ChungChi;
use App\Models\HocVien;
use App\Models\ThongTinUpload;
use App\Services\PinataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\select;

class ChungChiController extends Controller
{
    protected $pinataService;

    public function __construct(PinataService $pinataService)
    {
        $this->pinataService = $pinataService;
    }

    public function getDataTc()
    {
        $check = $this->isUserToChucCapChungChi();
        $data = ChungChi::join('hoc_viens', 'chung_chis.id_hoc_vien','hoc_viens.id')
        ->whereColumn('chung_chis.id_hoc_vien', 'hoc_viens.id')
        ->where('chung_chis.id_to_chuc', $check->id)
            ->whereNotNull('token')
            ->select('chung_chis.*', 'hoc_viens.ho_ten','hoc_viens.email','hoc_viens.so_cccd' ,'hoc_viens.ngay_sinh')
            ->get();
        return response()->json([
            'data' => $data,
        ]);
    }
    public function getDataNft()
    {
        $check = $this->isUserHocVien();
        $data = ChungChi::join('hoc_viens', 'chung_chis.id_hoc_vien','hoc_viens.id')
                        ->join('to_chuc_cap_chung_chis', 'chung_chis.id_to_chuc','to_chuc_cap_chung_chis.id')
                        ->where('chung_chis.id_hoc_vien', $check->id)
                        ->where('chung_chis.tinh_trang', ChungChi::TINH_TRANG_DA_CAP_NFT)
                        ->select('chung_chis.*', 'hoc_viens.ho_ten','hoc_viens.email','hoc_viens.so_cccd' ,'hoc_viens.ngay_sinh', 'to_chuc_cap_chung_chis.ten_to_chuc')
                        ->get();

        return response()->json([
            'data' => $data,
        ]);
    }
    public function changeVoHieuHoa(Request $request)
    {
        $user = $this->isUserToChucCapChungChi();
        $chung_chi = ChungChi::where('id', $request->id)->first();

        if ($chung_chi) {
            if ($chung_chi->tinh_trang == 2) {
                $chung_chi->tinh_trang = 3;
                $chung_chi->ghi_chu = $request ->ghi_chu;
                $chung_chi->save();

                return response()->json([
                    'status' => true,
                    'message' => "Vô hiệu hóa thành công"
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => "Có lỗi xảy ra"
            ]);
        }
    }
    public function getDataHv()
    {
        $check = $this->isUserHocVien();
        $data = ChungChi::join('hoc_viens', 'chung_chis.id_hoc_vien','hoc_viens.id')
        ->join('to_chuc_cap_chung_chis', 'chung_chis.id_to_chuc','to_chuc_cap_chung_chis.id')
        ->where('chung_chis.id_hoc_vien', $check->id)
            ->where('chung_chis.tinh_trang', ChungChi::TINH_TRANG_CHO_THANH_TOAN)
            ->select('chung_chis.*', 'hoc_viens.ho_ten','hoc_viens.email','hoc_viens.so_cccd' ,'hoc_viens.ngay_sinh', 'to_chuc_cap_chung_chis.ten_to_chuc')
            ->get();
        return response()->json([
            'data' => $data,
        ]);
    }
    public function taoChungChi(Request $request)
    {
        $check = $this->isUserToChucCapChungChi();
        if ($check) {
            $chung_chi = ThongTinUpload::where('thong_tin_uploads.id', $request->id)
                ->join('to_chuc_cap_chung_chis', 'thong_tin_uploads.id_to_chuc', 'to_chuc_cap_chung_chis.id')
                ->join('yeu_cau_caps', 'to_chuc_cap_chung_chis.id', 'yeu_cau_caps.id_to_chuc')
                ->whereColumn('thong_tin_uploads.email', 'yeu_cau_caps.email')
                ->join('hoc_viens', 'yeu_cau_caps.id_hoc_vien', 'hoc_viens.id')
                ->select('thong_tin_uploads.*', 'hoc_viens.id as id_hoc_vien')
                ->first();

            if ($chung_chi) {
                ChungChi::create([
                    'so_hieu_chung_chi' => $chung_chi->so_hieu_chung_chi,
                    'id_hoc_vien' => $chung_chi->id_hoc_vien,
                    'id_to_chuc' => $chung_chi->id_to_chuc,
                    'so_tien' => 2000,
                    'hinh_anh' => $chung_chi->hinh_anh,
                    'khoa_hoc' => $chung_chi->khoa_hoc,
                    'trinh_do' => $chung_chi->trinh_do,
                    'ngay_cap' => $chung_chi->ngay_cap,
                    'ket_qua' => $chung_chi->ket_qua,
                    'tinh_trang' => 0,
                ]);
                return response()->json([
                    'message'  =>   'Tạo thành công',
                    'status'   =>   true,
                ]);
            } else {
                return response()->json([
                    'message'  =>   'Có lỗi xảy ra',
                    'status'   =>   false,
                ]);
            }
        }else{
            return response()->json([
                'message'  =>   'Có lỗi xảy ra',
                'status'   =>   false,
            ]);
        }
    }
    public function getDataADChungChi()
    {
        $check = $this->isUserAdmin();
        $data = ChungChi::where(function ($query) {
            $query->where('tinh_trang', ChungChi::TINH_TRANG_DA_CAP_NFT)
                  ->orWhere('tinh_trang', ChungChi::TINH_TRANG_DA_VO_HIEU_HOA);
        })
        ->join('hoc_viens', 'chung_chis.id_hoc_vien', '=', 'hoc_viens.id')
        ->join('to_chuc_cap_chung_chis', 'chung_chis.id_to_chuc', '=', 'to_chuc_cap_chung_chis.id')
        ->select(
            'chung_chis.*',
            'hoc_viens.ho_ten',
            'hoc_viens.email',
            'hoc_viens.so_cccd',
            'hoc_viens.ngay_sinh',
            'to_chuc_cap_chung_chis.ten_to_chuc'
        )
        ->get();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function getDataCapNft()
    {
        $check = $this->isUserAdmin();
        $data  = ChungChi::join('hoc_viens', 'chung_chis.id_hoc_vien','hoc_viens.id')
                         ->join('to_chuc_cap_chung_chis', 'chung_chis.id_to_chuc','to_chuc_cap_chung_chis.id')
                         ->where('chung_chis.tinh_trang', ChungChi::TINH_TRANG_DA_THANH_TOAN)
                         ->select(
                            'chung_chis.*',
                            'hoc_viens.ho_ten',
                            'hoc_viens.email',
                            'hoc_viens.so_cccd' ,
                            'hoc_viens.ngay_sinh',
                            'to_chuc_cap_chung_chis.ten_to_chuc',
                         )
                         ->get();

        return response()->json([
            'data' => $data,
        ]);

    }

    public function mintNFTtoApi($address, $metadataUri)
{
    try {
        $client = new \GuzzleHttp\Client();
        $res = $client->post("http://localhost:3000/api/mint-nft", [
            'json' => [
                'recipient' => $address,
                'tokenURI'  => $metadataUri
            ]
        ]);

        return json_decode($res->getBody(), true);
    } catch (\GuzzleHttp\Exception\RequestException $e) {
        Log::error('Mint NFT error: ' . $e->getMessage());
        return [
            'success' => false,
            'error' => 'Minting failed: ' . $e->getMessage()
        ];
    }
}

public function createCapNft(Request $request)
{
     $id_chuc_nang = 6;
        $user = $this->isUserAdmin();
        $checkQuyen = ChiTietCapQuyen::where('id_chuc_vu', $user->id_chuc_vu)->where('id_chuc_nang', $id_chuc_nang)->first();
        if (!$checkQuyen) {
            return response()->json([
                'message'  =>   'Bạn chưa được cấp quyền này',
                'status'   =>   false,
            ]);
        }
    $this->isUserAdmin();

    $sinh_vien = HocVien::where('hoc_viens.id', $request->id_hoc_vien)
                        ->join('vi_nfts', 'hoc_viens.id', 'vi_nfts.id_hoc_vien')
                        ->select('vi_nfts.*')
                        ->first();

    if (!$sinh_vien) {
        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy sinh viên hoặc ví NFT.'
        ], 404);
    }

    // Chuẩn metadata chuẩn OpenSea
    $metadata = [
        "name"        => "Chứng chỉ khóa học - " . $request->ho_ten,
        "description" => "Chứng chỉ số cho học viên {$request->ho_ten} tốt nghiệp khóa học {$request->khoa_hoc}.",
        "image"       => $request->hinh_anh,
        "external_url"=> null,
        "attributes"  => [
            ["trait_type" => "Họ tên", "value" => $request->ho_ten],
            ["trait_type" => "Email", "value" => $request->email],
            ["trait_type" => "Ngày sinh", "value" => $request->ngay_sinh],
            ["trait_type" => "Số CCCD", "value" => $request->so_cccd],
            ["trait_type" => "Trình độ", "value" => $request->trinh_do],
            ["trait_type" => "Khóa học", "value" => $request->khoa_hoc],
            ["trait_type" => "Ngày cấp", "value" => $request->ngay_cap],
            ["trait_type" => "Kết quả", "value" => $request->ket_qua],
            ["trait_type" => "Tổ chức cấp", "value" => $request->ten_to_chuc],
            ["trait_type" => "Số hiệu CC", "value" => $request->so_hieu_chung_chi],
            ["trait_type" => "Ngày tạo", "value" => $request->created_at],
        ]
    ];

    // Upload metadata lên IPFS qua Pinata
    $metadataUri = $this->pinataService->uploadMetadata($metadata);

    // Gọi API mint NFT
    $txHash = $this->mintNFTtoApi($sinh_vien->dia_chi_vi, $metadataUri);

    if (!isset($txHash['success']) || !$txHash['success']) {
        return response()->json([
            'success' => false,
            'message' => 'Mint NFT thất bại',
            'error'   => $txHash['error'] ?? 'Không xác định'
        ], 500);
    }

    // Cập nhật chứng chỉ
    $chung_chi = ChungChi::find($request->id);
    if ($chung_chi) {
        $chung_chi->token = $txHash['transactionHash'];
        $chung_chi->MetaData_URL = $metadataUri;
        $chung_chi->tinh_trang = ChungChi::TINH_TRANG_DA_CAP_NFT;
        $chung_chi->save();
    }

    return response()->json([
        'success' => true,
        'message' => 'Mint NFT thành công'
    ]);
}

}
