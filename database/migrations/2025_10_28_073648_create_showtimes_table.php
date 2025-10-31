<?php
// database/migrations/2025_10_28_000000_create_showtimes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('showtimes', function (Blueprint $t) {
            $t->id();
            $t->foreignId('movie_id')->constrained()->cascadeOnDelete();
        
            $t->unsignedInteger('episode_number')->nullable(); // 👈 đưa ngay sau movie_id
        
            $t->date('show_date');
            $t->time('start_time');
            $t->time('end_time')->nullable();
            $t->string('room')->nullable();
            $t->boolean('is_premiere')->default(false);
            $t->string('note', 255)->nullable();
            $t->timestamps();
        
            $t->unique(['movie_id','show_date','start_time']);
            $t->index(['show_date','start_time']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('showtimes');
    }
};
