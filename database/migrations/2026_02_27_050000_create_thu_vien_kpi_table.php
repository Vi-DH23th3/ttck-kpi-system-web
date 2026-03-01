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
        Schema::create('thu_vien_kpi', function (Blueprint $table) {
//             Công việc
// KPT cần đạt
// Id_nam_hoc
// Ghi chú

            $table->id();
            $table->string('ten_cong_viec');
            $table->string('chi_tieu_kpi');
            $table->foreignId('dm_cv_id')->constrained('danhmuc_cong_viec')->onDelete('cascade');
            $table->foreignId('nam_hoc_id')->constrained('nam_hoc')->onDelete('cascade');
            $table->string('ghi_chu')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpi_mau');
    }
};
