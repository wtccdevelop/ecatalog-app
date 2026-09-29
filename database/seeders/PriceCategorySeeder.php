<?php

namespace Database\Seeders;

use App\Models\PriceCategories;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PriceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $cats = [
            ['1 Jutaan', 1_000_000, 1_999_999],
            ['2 Jutaan', 2_000_000, 2_999_999],
            ['3 Jutaan', 3_000_000, 3_999_999],
            ['4 Jutaan', 4_000_000, 4_999_999],
            ['5 Jutaan+', 5_000_000, null],
            ['Entry Level', null, null],
            ['Midrange', null, null],
            ['Flagship', null, null],
        ];

        foreach ($cats as $i => [$name, $min, $max]) {
            PriceCategories::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'min_price' => $min, 'max_price' => $max, 'sort_order' => $i + 1]
            );
        }
    }
}