<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViNft extends Model
{
    protected $table = 'vi_nfts';
    protected $fillable = [
        'id_hoc_vien',
        'dia_chi_vi',
    ];
}
