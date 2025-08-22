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
        Schema::create('chi_tiet_don_hangs', function (Blueprint $table) {
            $table->id();
            $table->integer('id_chung_chi');
            $table->integer('id_hoc_vien');
            $table->unsignedBigInteger('id_don_hang')->nullable();
            $table->integer('so_tien');
            $table->timestamps();
            $table->foreign('id_don_hang')
                  ->references('id')->on('don_hangs')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_don_hangs');
    }

};
