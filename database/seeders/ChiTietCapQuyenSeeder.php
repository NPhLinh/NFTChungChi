<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChiTietCapQuyenSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('chi_tiet_cap_quyens')->delete();
        DB::table('chi_tiet_cap_quyens')->truncate();
        DB::table('chi_tiet_cap_quyens')->insert([
            [
                'id_chuc_vu'             =>    1,
                'id_chuc_nang'             =>  1,
            ],
            [
                'id_chuc_vu'             =>    1,
                'id_chuc_nang'             =>  2,
            ],
            [
                'id_chuc_vu'             =>    1,
                'id_chuc_nang'             =>  3,
            ],
            [
                'id_chuc_vu'             =>    1,
                'id_chuc_nang'             =>  4,
            ],
            [
                'id_chuc_vu'             =>    1,
                'id_chuc_nang'             =>  5,
            ],
            [
                'id_chuc_vu'             =>    1,
                'id_chuc_nang'             =>  6,
            ],
        ]);
    }
}
