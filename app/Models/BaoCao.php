<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaoCao extends Model
{
    protected $table = 'bao_caos';

    protected $fillable = [
        'tieu_de',
        'noi_dung',
        'thoi_gian',
        'id_nhan_vien',
    ];
}
