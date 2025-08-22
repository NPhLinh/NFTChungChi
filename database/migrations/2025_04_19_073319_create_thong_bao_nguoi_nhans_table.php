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
        Schema::create('thong_bao_nguoi_nhans', function (Blueprint $table) {
            $table->id();
            $table->integer('id_thong_bao');
            $table->integer('id_hoc_vien')->nullable();
            $table->integer('id_to_chuc')->nullable();
            $table->integer('xem')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thong_bao_nguoi_nhans');
    }
};
