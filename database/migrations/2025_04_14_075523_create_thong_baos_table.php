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
        Schema::create('thong_baos', function (Blueprint $table) {
            $table->id();
            $table->string('tieu_de');
            $table->string('noi_dung');
            $table->integer('loai_nhan');
            $table->integer('doi_tuong')->nullable();
            $table->integer('id_hoc_vien')->nullable();
            $table->integer('id_to_chuc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thong_baos');
    }
};
