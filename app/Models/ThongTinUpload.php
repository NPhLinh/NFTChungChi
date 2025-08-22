<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ThongTinUpload extends Model
{
    use HasFactory;
    protected $table = 'thong_tin_uploads';
    protected $fillable = [
        'id_to_chuc',
        'so_hieu_chung_chi',
        'hinh_anh',
        'khoa_hoc',
        'trinh_do',
        'ngay_cap',
        'ket_qua',
        'ho_ten',
        'so_cccd',
        'sdt',
        'email',
    ];
}
