<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable(); // <-- đặt ngay sau id, bỏ after()
            $table->foreign('parent_id')->references('id')->on('comments')->onDelete('cascade');
        
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();
            $table->text('content');
            $table->timestamps();
        });
        
    }

    public function down(): void {
        Schema::dropIfExists('comments');
    }
};
