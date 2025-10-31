<?php
// database/migrations/2025_09_27_000000_add_gender_to_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasColumn('users', 'gender')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('gender', ['male','female','unknown'])
                      ->default('unknown')
                      ->after('email');
            });
        }
    }
    public function down(): void {
        if (Schema::hasColumn('users', 'gender')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('gender');
            });
        }
    }
};
