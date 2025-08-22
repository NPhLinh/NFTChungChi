<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichSuGiaoDich extends Model
{
    protected $table = 'lich_su_giao_diches';
    protected $fillable = [
        'url_thanh_toan',
        'MetaData_URL',
        'thoi_gian',
        'so_tien',
        'noi_dung',
        'id_chung_chi'
    ];
}
