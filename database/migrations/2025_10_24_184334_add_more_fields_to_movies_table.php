<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            // Thêm cột nền tảng nếu chưa có
            if (!Schema::hasColumn('movies', 'is_series')) {
                $table->boolean('is_series')->default(false);
            }
            if (!Schema::hasColumn('movies', 'poster')) {
                $table->string('poster')->nullable();
            }

            // Các cột mới (KHÔNG dùng after())
            if (!Schema::hasColumn('movies', 'english_title')) {
                $table->string('english_title')->nullable();
            }
            if (!Schema::hasColumn('movies', 'version')) {
                $table->string('version', 20)->nullable(); // sub/dub/raw...
            }
            if (!Schema::hasColumn('movies', 'total_seasons')) {
                $table->unsignedSmallInteger('total_seasons')->default(0);
            }
            if (!Schema::hasColumn('movies', 'age_rating')) {
                $table->string('age_rating', 10)->nullable();
            }
            if (!Schema::hasColumn('movies', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('movies', 'banner')) {
                $table->string('banner')->nullable();
            }
            if (!Schema::hasColumn('movies', 'file_name')) {
                $table->string('file_name')->nullable();
            }
            if (!Schema::hasColumn('movies', 'video_path')) {
                $table->string('video_path')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            foreach ([
                'english_title','version','total_seasons','age_rating',
                'description','poster','banner','file_name','video_path','is_series'
            ] as $col) {
                if (Schema::hasColumn('movies', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
