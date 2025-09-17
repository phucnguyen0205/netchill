<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Hành động','Hài','Kinh dị','Tình cảm','Phiêu lưu'];
        foreach($categories as $cat){
            Category::create(['name'=>$cat]);
        }
    }
}
