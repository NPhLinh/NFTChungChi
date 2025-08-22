<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ChiTietDonHang;
use Carbon\Carbon;

class DonHang extends Model
{
    use HasFactory;
    protected $table = 'don_hangs';
    protected $fillable = [
        'ma_don_hang',
        'tong_tien_thanh_toan',
        'is_thanh_toan',
        'ho_ten',
        'email',
        'id_hoc_vien'
    ];
    public function getCreatedAtAttribute($value)
{
    return Carbon::parse($value)->setTimezone('Asia/Ho_Chi_Minh')->format('H:i:s d/m/Y');
}
    public function getUpdatedAtAttribute($value)
{
    return Carbon::parse($value)->setTimezone('Asia/Ho_Chi_Minh')->format('H:i:s d/m/Y');
}
    public function chiTiet()
    {
        return $this->hasMany(ChiTietDonHang::class, 'id_don_hang');
    }

    // Gắn observer để xóa quan hệ
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($donHang) {
            // xóa luôn các detail
            $donHang->chiTiet()->delete();
        });
    }

}
