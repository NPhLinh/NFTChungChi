<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThongKe extends Model
{
    protected $table = 'thong_kes';
    protected $fillable = [
        'thoi_gian',
        'so_luong_giao_dich',
        'tong_doanh_thu',
    ];
}
