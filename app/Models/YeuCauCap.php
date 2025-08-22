<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class YeuCauCap extends Model
{
    use HasFactory;
    protected $table = 'yeu_cau_caps';

    protected $fillable = [
        'id_to_chuc',
        'id_hoc_vien',
        'ho_ten',
        'so_cccd',
        'email',
        'so_hieu_chung_chi',
        'trang_thai'
    ];
public function getCreatedAtAttribute($value)
{
    return Carbon::parse($value)->setTimezone('Asia/Ho_Chi_Minh')->format('H:i:s d/m/Y');
}


}
