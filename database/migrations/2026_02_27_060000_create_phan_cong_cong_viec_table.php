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
        Schema::create('phan_cong_cong_viec', function (Blueprint $table) {
            
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kpi_id')->constrained('thu_vien_kpi')->onDelete('cascade');
            $table->date('ngay_bat_dau');
            $table->date('ngay_ket_thuc');
            $table->string('trang_thai')->default('chua_bat_dau'); //chua_bat_dau, dang_thuc_hien, da_hoan_thanh, qua_han
            $table->string('ghi_chu')->nullable();
            $table->integer('muc_do_uu_tien')->default(1); // 1: Thấp, 2: Trung bình, 3: Cao
            $table->foreignId('user_phan_cong_id')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phan_cong_cong_viec');
    }
};
