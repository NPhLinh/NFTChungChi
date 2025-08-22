<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ThongBao extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'thong_baos';
    protected $fillable = [
        'tieu_de',
        'noi_dung',
        'loai_nhan',
        'doi_tuong',
        'id_hoc_vien',
        'id_to_chuc'
    ];
    public function getCreatedAtAttribute($value)
{
    return Carbon::parse($value)->setTimezone('Asia/Ho_Chi_Minh')->format('H:i:s d/m/Y');
}
}
