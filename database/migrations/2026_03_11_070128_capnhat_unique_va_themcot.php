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
         Schema::table('thu_vien_kpi', function (Blueprint $table) {
            $table->unique([
                'ten_kpi',
                'dm_cv_id',
                'chi_tieu',
                'don_vi',
                'chu_ky'
            ], 'unique_kpi');
        });
        Schema::table('danhmuc_cong_viec', function (Blueprint $table) {
            $table->unique([
                'ten_cong_viec',
                'don_vi_id'
            ], 'unique_danhmuc_congviec');
        });
        Schema::table('bao_cao_cong_viec', function (Blueprint $table) {

            // số file minh chứng đã nộp
            $table->integer('tien_do_thuc')
                ->default(0);

            // số lần báo cáo
            $table->integer('so_lan_bao_cao')
                ->default(0);
        });
        Schema::table('phan_cong_cong_viec', function (Blueprint $table) {

            // số file minh chứng đã nộp
            $table->integer('thuc_te_dat_duoc')
                ->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
