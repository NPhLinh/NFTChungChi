<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ChungChi extends Model
{
    use HasFactory;
    protected $table = 'chung_chis';
    protected $fillable = [
        'so_hieu_chung_chi',
        'id_hoc_vien',
        'id_to_chuc',
        'so_tien',
        'hinh_anh',
        'token',
        'MetaData_URL',
        'khoa_hoc',
        'trinh_do',
        'ngay_cap',
        'ket_qua',
        'tinh_trang',
        'ghi_chu',
    ];

    public function getCreatedAtAttribute($value)
{
    return Carbon::parse($value)->setTimezone('Asia/Ho_Chi_Minh')->format('H:i:s d/m/Y');
}

    CONST TINH_TRANG_CHO_THANH_TOAN         = 0;
    CONST TINH_TRANG_DA_THANH_TOAN          = 1;
    CONST TINH_TRANG_DA_CAP_NFT             = 2;
    CONST TINH_TRANG_DA_VO_HIEU_HOA         = 3;
}
