<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            'Việt Nam',
            'Hoa Kỳ',
            'Anh',
            'Hàn Quốc',
            'Nhật Bản',
            'Trung Quốc',
            'Thái Lan',
            'Ấn Độ',
            'Pháp',
            'Đức',
            'Tây Ban Nha',
            'Ý',
            'Canada',
            'Úc',
            'Đài Loan',
        ];

        foreach ($countries as $name) {
            Country::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
