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
            $table->foreignId('kpi_id')->constrained('thu_vien_kpi')->onDelete('cascade');
            $table->string('loai_kpi')->default('don_gian'); 
            $table->integer('so_lan_toi_thieu_thang')->nullable();  // ví dụ: 1 lần/tháng (nếu có)
            $table->integer('chu_ky_thang')->nullable(); // ví dụ:1 tháng/lần hoặc 3 tháng/lần (nếu có)
            $table->json('dieu_kien_phu')->nullable(); // ví dụ: {"sv_moi_lop": 15}
            $table->boolean('cho_phep_bu')->default(false);  // có cho bù sang năm sau không
            $table->integer('nguong_duoc_bu')->nullable(); // đạt tối thiểu bao nhiêu mới được bù
            $table->date('ngay_bat_dau');
            $table->date('ngay_ket_thuc');
            $table->foreignId('nam_hoc_id')->constrained('nam_hoc')->onDelete('cascade');
            $table->string('ghi_chu')->nullable();
            $table->foreignId('user_phan_cong_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('trang_thai')->default('chua_bat_dau'); 
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
