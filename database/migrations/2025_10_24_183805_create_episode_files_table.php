<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('episode_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('episode_id')->constrained()->cascadeOnDelete();

            $table->string('label')->nullable();                  // "Server 1", "VIP", v.v.
            $table->string('quality')->nullable();                // "360p","720p","1080p","HLS"
            $table->string('format')->nullable();                 // "mp4","m3u8"
            $table->string('path');                               // storage path / URL
            $table->boolean('is_default')->default(false);

            $table->timestamps();
            $table->softDeletes();

            // 1 episode có thể có nhiều record, nhưng tránh trùng label+quality
            $table->unique(['episode_id','label','quality']);
            $table->index(['episode_id','deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('episode_files');
    }
};
