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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('don_vi_id')->nullable()->constrained('don_vi')->nullOnDelete();
            $table->foreignId('chuc_vu_id')->nullable()->constrained('chuc_vu')->nullOnDelete(); // Giám đốc, Trưởng phòng, Phó trưởng phòng, Nhân viên
            $table->string('role')->default('staff')->after('password'); // admin, manager, staff      
            $table->integer('trang_thai')->default(1); // 1: Kích hoạt, 0: Vô hiệu hóa
            $table->string('avatar')->nullable()->after('id');
            $table->integer('must_change_password')->default(0)->after('trang_thai'); // 0: không bắt buộc, 1: bắt buộc đổi mật khẩu sau khi đăng nhập lần đầu
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['don_vi_id']);
            $table->dropColumn(['don_vi_id', 'role', 'chucvu']);
            $table->dropSoftDeletes();
        });
    }
};
