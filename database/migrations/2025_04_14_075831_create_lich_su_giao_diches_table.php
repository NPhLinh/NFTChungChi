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
        Schema::create('lich_su_giao_diches', function (Blueprint $table) {
            $table->id();
            $table->string('url_thanh_toan');
            $table->string('MetaData_URL');
            $table->string('thoi_gian');
            $table->string('so_tien');
            $table->string('noi_dung');
            $table->integer('id_chung_chi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_su_giao_diches');
    }
};
