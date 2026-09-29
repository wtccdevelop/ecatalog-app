<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $A = 'assets/images/';

        $rows = [
            ['main',  'Banner 1', 'bannerwtccell.jpg'],
            ['small', 'Beli Vivo Y31d Pro sekarang, langsung dapat promo + hadiah keren sekaligus!', 'banner1.jpg'],
            ['small', 'PRE Order OPPO Find X9 Ultra — benefit hingga Rp9 juta!', 'banner2.jpg'],
        ];

        foreach ($rows as $i => [$type, $title, $file]) {
            Banner::updateOrCreate(
                ['image_path' => $A.$file],
                ['type' => $type, 'title' => $title, 'link_url' => null, 'sort_order' => $i + 1, 'is_active' => true]
            );
        }
    }
}