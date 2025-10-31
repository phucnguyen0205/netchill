<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('seasons', function (Blueprint $table) {
            // đổi cột thành có default 1
            $table->unsignedSmallInteger('season_number')->default(1)->change();
        });
    }

    public function down(): void
    {
        Schema::table('seasons', function (Blueprint $table) {
            // quay lại không default (nếu cần)
            $table->unsignedSmallInteger('season_number')->nullable(false)->default(null)->change();
        });
    }
};

