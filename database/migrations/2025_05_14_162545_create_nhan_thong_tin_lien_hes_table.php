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
        Schema::create('nhan_thong_tin_lien_hes', function (Blueprint $table) {
            $table->id();
            $table->string('ho_ten');
            $table->string('sdt');
            $table->string('tieu_de');
            $table->string('noi_dung');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nhan_thong_tin_lien_hes');
    }
};
