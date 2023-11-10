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
        Schema::create('tsanpham', function (Blueprint $table) {
            $table->increments('maSanPham'); // Tự động tăng
            $table->string('tenSanPham');
            $table->decimal('giaSanPham', 10, 2);
            $table->string('maLoai');
            $table->string('maThuongHieu');
            $table->integer('slTonKho')->nullable();
            $table->string('anhSP')->nullable();
            $table->text('moTaSP');
            $table->timestamp('ngayThemSP');
            $table->string('maSeri', 12);
            // Thêm các trường khác nếu cần
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tsanpham');
    }
};
