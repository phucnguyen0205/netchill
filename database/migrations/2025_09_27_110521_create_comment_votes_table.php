<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('comment_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // 1 = like, -1 = dislike
            $table->tinyInteger('value'); 
            $table->timestamps();

            $table->unique(['comment_id','user_id']); // 1 user chỉ được 1 vote / comment
            $table->index(['comment_id','value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_votes');
    }
};
