<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('thong_kes', function (Blueprint $table) {
            $table->id();
            $table->string('thoi_gian');
            $table->integer('so_luong_giao_dich');
            $table->string('tong_doanh_thu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thong_kes');
    }
};
