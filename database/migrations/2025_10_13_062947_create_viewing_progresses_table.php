<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('viewing_progresses')) {
            // Bảng đã tồn tại -> không tạo nữa
            return;
        }
        Schema::create('viewing_progresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('last_position')->default(0);
            $table->unsignedInteger('duration')->default(0);
            $table->unsignedTinyInteger('progress')->default(0);
            $table->timestamp('last_watched_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'movie_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('viewing_progresses');
    }
};
