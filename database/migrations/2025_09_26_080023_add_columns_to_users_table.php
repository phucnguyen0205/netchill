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
            // Thêm gender nếu chưa có
            if (!Schema::hasColumn('users', 'gender')) {
                $table->string('gender', 20)->nullable()->after('email');
            }
            
            // Thêm avatar nếu chưa có
            if (!Schema::hasColumn('users', 'avatar')) {
                 $table->string('avatar')->nullable()->after('name'); 
            }
            
            // Thêm cột role (Giả định bạn muốn thêm cột này)
            if (!Schema::hasColumn('users', 'role')) {
                 $table->string('role')->default('user')->after('gender'); 
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Xóa các cột chỉ khi chúng tồn tại
            if (Schema::hasColumn('users', 'gender')) {
                $table->dropColumn('gender');
            }
            if (Schema::hasColumn('users', 'avatar')) {
                $table->dropColumn('avatar');
            }
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};