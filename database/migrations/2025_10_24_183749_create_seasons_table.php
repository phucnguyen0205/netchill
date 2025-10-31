<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('season_number');
            $table->string('title')->nullable();
            $table->unsignedInteger('total_episodes')->default(0);
            $table->text('note')->nullable(); // <- đặt ở đây, không cần after()
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['movie_id','season_number']);
            $table->index(['movie_id','deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seasons');
    }
};


