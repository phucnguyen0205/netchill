<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('provider', 50)->default('vnpay');
            $table->string('txn_ref', 64)->unique();       // mã tham chiếu duy nhất
            $table->string('order_code', 64)->nullable()->index();
            $table->unsignedBigInteger('amount')->default(0);
            $table->string('currency', 10)->default('VND');
            $table->string('status', 20)->default('pending')->index();
            $table->string('provider_txn', 64)->nullable()->index();
            $table->timestamp('paid_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
