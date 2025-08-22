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
        Schema::create('thong_tin_uploads', function (Blueprint $table) {
            $table->id();
            $table->integer('id_to_chuc');
            $table->string('so_hieu_chung_chi')->nullable();
            $table->string('hinh_anh')->nullable();
            $table->string('khoa_hoc')->nullable();
            $table->string('trinh_do')->nullable();
            $table->string('ngay_cap')->nullable();
            $table->string('ket_qua')->nullable();
            $table->string('ho_ten')->nullable();
            $table->string('so_cccd')->nullable();
            $table->string('sdt')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thong_tin_uploads');
    }
};
