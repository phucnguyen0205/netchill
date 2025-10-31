<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('movie_watchlist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('watchlist_id')->constrained('watchlists')->cascadeOnDelete();
            $table->foreignId('movie_id')->constrained('movies')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['watchlist_id','movie_id']); // tránh trùng
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movie_watchlist');
    }
};
