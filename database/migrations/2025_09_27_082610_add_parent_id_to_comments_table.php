<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('comments', 'is_secret')) {
            Schema::table('comments', function (Blueprint $table) {
                // đặt sau content cho dễ quản lý; default false để bản ghi cũ hợp lệ
                $table->boolean('is_secret')->default(false)->after('content');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('comments', 'is_secret')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropColumn('is_secret');
            });
        }
    }
};
