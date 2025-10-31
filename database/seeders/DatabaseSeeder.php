<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Gọi lần lượt các seeder cần thiết
        $this->call([
            CategorySeeder::class,
            // GenreSeeder::class,        // nếu bạn có
            AdminUserSeeder::class,    // nếu muốn set quyền admin/user mặc định
            CountrySeeder::class,

        ]);
    }
}
