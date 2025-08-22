<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class HocVien extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "hoc_viens";
    protected $fillable = [
        'email',
        'password',
        'ho_ten',
        'ngay_sinh',
        'gioi_tinh',
        'so_cccd',
        'sdt',
        'dia_chi',
        'hinh_anh',
        'hash_reset',
        'is_duyet',
    ];
}
