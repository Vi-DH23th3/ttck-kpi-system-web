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
            $table->string('role')->default('staff')->after('password'); // admin, manager, staff
            $table->string('chucvu')->nullable()->after('role');
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
