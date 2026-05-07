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
            $table->id();
            $table->string('ten_kpi'); 
            $table->integer('chi_tieu')->nullable(); // 12
            $table->string('don_vi'); // lần, báo cáo, website
            $table->string('chu_ky'); // thang, quy, nam, 3_nam
            $table->foreignId('dm_cv_id')->constrained('danhmuc_cong_viec')->onDelete('cascade');
            $table->text('ghi_chu')->nullable();
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
