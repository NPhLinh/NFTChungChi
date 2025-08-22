<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NftGuiDen extends Model
{
   use HasFactory;

    protected $table = 'nft_gui_dens';

    protected $fillable = [
        'email_nguoi_nhan',
        'email_nguoi_gui',
        'token',
        'MetaData_URL',
    ];
   public function getCreatedAtAttribute($value)
{
    return Carbon::parse($value)->setTimezone('Asia/Ho_Chi_Minh')->format('H:i:s d/m/Y');
}
    public function getUpdatedAtAttribute($value)
{
    return Carbon::parse($value)->setTimezone('Asia/Ho_Chi_Minh')->format('H:i:s d/m/Y');
}
}
