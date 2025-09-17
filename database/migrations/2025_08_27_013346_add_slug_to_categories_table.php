<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_slug_to_categories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Thêm cột 'slug' với thuộc tính nullable
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        // 2. Cập nhật giá trị slug cho các bản ghi hiện có
        $categories = Category::all();
        foreach ($categories as $category) {
            $slug = Str::slug($category->name);
            $count = 1;
            $originalSlug = $slug;

            // Đảm bảo slug là duy nhất bằng cách thêm số vào cuối nếu cần
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }

            $category->slug = $slug;
            $category->save();
        }

        // 3. Thay đổi cột 'slug' để nó không cho phép null và thêm ràng buộc unique
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};