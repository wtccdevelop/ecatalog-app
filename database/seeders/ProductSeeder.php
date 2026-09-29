<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    private const A = 'assets/images/';

    public function run(): void
    {
        $catalog = [
            'APPLE' => [
                ['IPHONE AIR',        'KG9Ap3uxXINh1UiWdRn9G6v2rQSOsWQtHA4Lhinw.jpg', 16599000],
                ['IPHONE 17 PRO MAX', '3Ad84gw90PC9MsYVdV7MgQaBTz2LzUgjmG31zT6N.png', 25999000],
                ['IPHONE 17 PRO',     '1nmHT6TafI7XNbFKbSLQDefU81kbYoehOFaPWq5t.png', 22599000],
                ['IPHONE 17',         'Zr9rsrWHElCMMg1SAiKj5UzkN2Zv3zyst3o6nZES.png', 17999000],
            ],
            'SAMSUNG' => [
                ['SAMSUNG GALAXY Z FLIP 8',       '0vKJ7VF5ZGOeyk7inndQrxylwVEYXaQf1y4z1TcB.jpg', 18249000],
                ['SAMSUNG GALAXY Z FOLD 8 WIDE',  'claJOjigS0m7q3giee4578qC7CMHleKw7lRcwBfi.jpg', 26499000],
                ['SAMSUNG GALAXY Z FOLD 8 ULTRA', 'aqstPKkSCE7O9JkzpSEdzDoYlI7EF3CSmdpixfQn.jpg', 30299000],
                ['SAMSUNG GALAXY A07 LTE',         'ZPR2EjMlYq0bajlfvkhTUOyV8TBDvheBHUdiiBab.png', 2599000],
            ],
            'OPPO' => [
                ['OPPO RENO 16 PRO 5G', 'Ln8cw6hEMcqZkJmKDk4Jkhk3YMygxzgKi7SG5DJn.jpg', 14499000],
                ['OPPO RENO 16 5G',     'C7XOdXjH7YNzuyC0TmSWMny2IuXN41cy53zDfVRw.jpg', 11549000],
                ['OPPO RENO 16 F',      'NBDQ1sVRjsMqMoUYBr6D96L5TXn6IEFjsfaEiNaC.jpg', 7699000],
                ['OPPO FIND X9',        'fDNnhrqw0jd01G0jHG8DXJTf5bIQ9n8W8dgXBJPD.jpg', 15999000],
            ],
            'VIVO' => [
                ['VIVO Y31D',      'cIDrQezQ048AwZlCJDxbYR5zdJ614yKymJtbEMwT.jpg', 3499000],
                ['VIVO X300 ULTRA','F740vKgjYrpBWGc7qMIgKnV1YmaiTQV4LMYGGohv.jpg', 25299000],
                ['VIVO X300',      'cnIrZZRPqsLHzhW194HKndVwUVn1EnSquZk1UQzY.jpg', 14599000],
                ['VIVO Y31D PRO',  '4AENs1Dd3bn1DT8uTgEdR70LTBLwEPTM8wThQsXt.png', 4299000],
            ],
            'REALME' => [
                ['REALME C100I',   'koL4DaHUEGsn7XenJDBChCpckNv0o1YJoZXPfaG7.jpg', 2599000],
                ['REALME NOTE 80', 'RLMNr4fG6cc0hyCkbo7u3P84MfAIoGahI90ATbQM.png', 2399000],
                ['REALME C85 PRO', 'WUgq0jDIawMmu9mtT0G6RWxT36ckhY7ihUa3rJvk.png', 3999000],
                ['REALME C85 4G',  'a0Hs1Hft0vZwOZlsR7tgzHls5AIN0iYXpgh877Mr.png', 3299000],
            ],
            'XIAOMI' => [
                ['XIAOMI REDMI A7 PRO', '62mG1I6UEuMTwLZcKtoti8MGrewjj3vByhJQCxJt.jpg', 1949000],
                ['XIAOMI 17T PRO',      'jQE8bxq1RRAqAInmVeFx8OLjnxuhg42rbvhJGgEM.jpg', 12649000],
                ['XIAOMI 17T',          'DMDTrKjmPwJvfJdAXmUI54ZizMVe8URsWequRpUS.jpg', 9999000],
                ['XIAOMI 17',           'QD4ofucawMU42mghbYxu7LvCAziYXaCUtYQidSEw.jpg', 14599000],
            ],
            'INFINIX' => [
                ['INFINIX HOT 70',    'MWhx7XJt67wvOOsW5IyrkjVCiGkMbaNmEhEW1NBP.jpg', 2399000],
                ['INFINIX GT 50 PRO', 'rFE99SUNgYM0C6NOOmdTLSWaBK7d7ZTl0SUXGZVZ.jpg', 6999000],
                ['INFINIX SMART 20',  'per2S8FD6yK2Yin5iT0rxkINnsDUqQVatyDESjI0.png', 1849000],
                ['INFINIX NOTE 60',   'L7htds6v5i4VZ3sqozjwTa1nLYLhDl6CPsmqv3Bx.jpg', 4999000],
            ],
            'TECNO' => [
                ['TECNO POVA 8 5G',   'WTJD9Hv5MZL0GGCn9STnBnl0Ro7CEUllCxzKvGOV.jpg', 4299000],
                ['TECNO SPARK 50',    'jz9Vfyh82JcDHNDIN0QY7IZr5rGhRFPaTY8SX1mz.jpg', 2399000],
                ['TECNO CAMON 50 PRO','OrEG3diUCiqVJfVaQ6O5qfbOuVdEcrMXkLXwDxif.png', 5499000],
                ['TECNO CAMON 50',    'urg4vFa6CyOUAdjuMUQPemGiYFoa4uSviyQcm3TG.png', 4199000],
            ],
        ];

        foreach ($catalog as $brandName => $items) {
            $brand = Brand::where('slug', Str::slug($brandName))->firstOrFail();

            foreach ($items as $i => [$name, $file, $price]) {
                $product = Product::updateOrCreate(
                    ['slug' => Str::slug($name)],
                    [
                        'brand_id'   => $brand->id,
                        'name'       => $name,
                        'image_path' => self::A.$file,
                        'is_active'  => true,
                        'sort_order' => $i + 1,
                    ]
                );

                // varian default (belum ada RAM/storage/warna) — hanya jika belum punya varian
                if ($product->variants()->doesntExist()) {
                    $product->variants()->create([
                        'ram' => null, 'storage' => null, 'color' => null,
                        'price' => $price, 'stock' => 10, 'is_active' => true,
                    ]);
                }
            }
        }

        $this->seedIphoneAirDetail();
    }

    private function seedIphoneAirDetail(): void
    {
        $product = Product::where('slug', 'iphone-air')->firstOrFail();

        // ganti varian default dengan varian lengkap
        $product->variants()->delete();
        foreach ([
            [12, 256,  'SKY BLUE',    16599000, 10],
            [12, 256,  'SPACE BLACK', 16599000, 6],
            [12, 512,  'SKY BLUE',    20599000, 8],
            [12, 1024, 'SKY BLUE',    25599000, 10],
        ] as [$ram, $storage, $color, $price, $stock]) {
            $product->variants()->create(compact('ram', 'storage', 'color', 'price', 'stock') + ['is_active' => true]);
        }

        $product->specs()->delete();
        foreach ([
            ['Chipset',      'Apple A19 Pro (3 nm)'],
            ['Display',      '6.5 inches, LTPO Super Retina XDR OLED, 120Hz'],
            ['Battery',      '3149 mAh'],
            ['OS',           'iOS 26, upgradable to iOS 26.2'],
            ['Resolution',   '1260 x 2736 pixels'],
            ['Main Camera',  '48 MP'],
            ['Front Camera', '18 MP'],
            ['Weight',       '165 g'],
        ] as $i => [$label, $value]) {
            $product->specs()->create(['label' => $label, 'value' => $value, 'sort_order' => $i + 1]);
        }

        $product->reviews()->delete();
        foreach ([
            ['Budi S.', 5, '2026-09-12', 'Barang original, segel rapi, pengiriman cepat. Mantap!'],
            ['Rina A.', 4, '2026-09-03', 'Pelayanan ramah, dibantu setup cicilan lewat WhatsApp.'],
        ] as [$name, $rating, $date, $comment]) {
            $product->reviews()->create([
                'name' => $name, 'rating' => $rating, 'comment' => $comment,
                'created_at' => $date, 'updated_at' => $date,
            ]);
        }
    }
}
