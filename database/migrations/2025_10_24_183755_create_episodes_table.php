<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('season_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('episode_number');            // 1..N trong mùa
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('duration')->nullable();      // giây
            $table->string('status')->default('draft');           // draft|published|hidden...
            $table->timestamp('published_at')->nullable();

            // nếu bạn dùng upload chunk như phim lẻ:
            $table->string('file_name')->nullable();              // tên file sau merge
            $table->string('video_path')->nullable();             // đường dẫn thực tế

            $table->timestamps();
            $table->softDeletes();

            // Mỗi mùa chỉ có 1 episode_number duy nhất
            $table->unique(['season_id','episode_number']);
            $table->index(['season_id','deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};
