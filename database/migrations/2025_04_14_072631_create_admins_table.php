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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('ho_ten');
            $table->string('ngay_sinh');
            $table->string('gioi_tinh');
            $table->string('so_cccd');
            $table->string('sdt');
            $table->string('dia_chi');
            $table->string('hinh_anh')->nullable();
            $table->string('hash_reset')->nullable();
            $table->integer('id_chuc_vu')->nullable();
            $table->integer('is_duyet');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
