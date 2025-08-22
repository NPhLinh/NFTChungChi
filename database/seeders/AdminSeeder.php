<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admins')->delete();
        DB::table('admins')->truncate();
        DB::table('admins')->insert([
            [
                'email'             =>  'admin@gmail.com',
                'password'          =>  bcrypt('123456'),
                'ho_ten'         =>  'Admin',
                'ngay_sinh'     =>  '2000-01-01',
                'gioi_tinh'           =>  '1',
                'so_cccd'           =>  '04009912345678',
                'sdt'           =>  '0123456789',
                'dia_chi'           =>  'Đà Nẵng',
                'hinh_anh'           =>  null,
                'hash_reset'           =>  null,
                'id_chuc_vu'           =>  '1',
                'is_duyet'           =>  1,
            ],
        ]);
    }
}
