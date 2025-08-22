<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NhanThongTinLienHe extends Model
{
    use HasFactory;
    protected $table = "nhan_thong_tin_lien_hes";
    protected $fillable = [
        'ho_ten',
        'sdt',
        'tieu_de',
        'noi_dung',
    ];
    public function getCreatedAtAttribute($value)
{
    return Carbon::parse($value)->setTimezone('Asia/Ho_Chi_Minh')->format('H:i:s d/m/Y');
}
}
