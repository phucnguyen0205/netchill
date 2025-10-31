<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_banners_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');              // đường dẫn ảnh (trên disk public)
            $table->string('variant')->default('hero'); // hero|mobile
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        
            $table->index(['variant','created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};