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
        Schema::create('to_chuc_cap_chung_chis', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('ten_to_chuc');
            $table->string('hotline');
            $table->string('dia_chi');
            $table->string('ho_ten_nguoi_dai_dien');
            $table->string('so_cccd');
            $table->string('sdt_nguoi_dai_dien');
            $table->string('email_nguoi_dai_dien');
            $table->string('hinh_anh')->nullable();
            $table->string('hash_reset')->nullable();
            $table->integer('is_duyet');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('to_chuc_cap_chung_chis');
    }
};
