<?php

namespace Database\Seeders;

use App\Models\ChucNang;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
   public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            ChucVuSeeder::class,
            ChucNangSeeder::class,
            ChiTietCapQuyenSeeder::class,
        ]);
    }
}
