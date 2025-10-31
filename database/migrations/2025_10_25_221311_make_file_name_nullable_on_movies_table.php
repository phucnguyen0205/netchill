<?php
// database/migrations/xxxx_xx_xx_xxxxxx_make_file_name_nullable_on_movies_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->string('file_name')->nullable()->change();
            $table->string('video_path')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            // nếu muốn quay lại NOT NULL:
            $table->string('file_name')->nullable(false)->change();
            $table->string('video_path')->nullable(false)->change();
        });
    }
};
