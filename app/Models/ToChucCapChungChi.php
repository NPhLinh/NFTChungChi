<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ToChucCapChungChi extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "to_chuc_cap_chung_chis";
    protected $fillable = [
        'email',
        'password',
        'ten_to_chuc',
        'hotline',
        'dia_chi',
        'ho_ten_nguoi_dai_dien',
        'so_cccd',
        'sdt_nguoi_dai_dien',
        'email_nguoi_dai_dien',
        'hinh_anh',
        'hash_reset',
        'is_duyet',
    ];
}
