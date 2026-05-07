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
        Schema::create('chi_tiet_phan_cong', function (Blueprint $table) {
            $table->id();
            $table->foreignId('phan_cong_id')->constrained('phan_cong_cong_viec')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->float('thuc_te_dat_duoc')->default(0);
            $table->string('trang_thai')->default('chua_bat_dau'); 
            $table->integer('thong_bao')->default(0); 
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_phan_cong');
    }
};
