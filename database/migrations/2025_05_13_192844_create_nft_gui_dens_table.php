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
        Schema::create('nft_gui_dens', function (Blueprint $table) {
            $table->id();
            $table->string('email_nguoi_nhan');
            $table->string('email_nguoi_gui');
            $table->string('token');
            $table->string('MetaData_URL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nft_gui_dens');
    }
};
