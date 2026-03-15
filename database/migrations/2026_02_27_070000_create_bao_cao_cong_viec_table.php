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
        Schema::create('bao_cao_cong_viec', function (Blueprint $table) {
            
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('phan_cong_id')->constrained('phan_cong_cong_viec')->onDelete('cascade');
            //$table->foreignId('kpi_mau_id')->constrained('kpi_mau')->onDelete('cascade');
            $table->date('ngay_thuc_hien');
            $table->string('trang_thai_bao_cao')->default('dang_lam'); //đang làm, đã nộp, đã chỉnh sửa
            $table->string('file_minh_chung')->nullable();
            $table->foreignId('user_duyet_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('ghi_chu')->nullable();
            $table->string('trangthai_duyet')->default('chua_duyet'); // chua_duyet, da_duyet, tu_choi
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bao_cao_cong_viec');
    }
};
