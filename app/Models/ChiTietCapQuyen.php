<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietCapQuyen extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_cap_quyens';

    protected $fillable = [
        'id_chuc_vu',
        'id_chuc_nang',
    ];
}
