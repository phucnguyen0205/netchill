<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            // thêm nếu chưa có, dùng smallInteger cho gọn
            if (!Schema::hasColumn('movies', 'release_year')) {
                $table->smallInteger('release_year')->nullable()->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            if (Schema::hasColumn('movies', 'release_year')) {
                $table->dropColumn('release_year');
            }
        });
    }
};
