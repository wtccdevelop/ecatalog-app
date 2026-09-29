<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $A = 'assets/images/';

        // urutan = urutan di brand grid (brands.js)
        $brands = [
            ['SAMSUNG', '2.png'], ['OPPO', '3.png'], ['VIVO', '4.png'], ['REALME', '5.png'],
            ['XIAOMI', '6.png'],  ['INFINIX', '7.png'], ['TECNO', '8.png'], ['APPLE', '9.png'],
        ];

        foreach ($brands as $i => [$name, $logo]) {
            Brand::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'logo_path' => $A.$logo, 'sort_order' => $i + 1, 'is_active' => true]
            );
        }
    }
}
