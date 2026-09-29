<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Benefits;

class BenefitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $A = 'assets/images/';
        $rows = [
            ['Product 100% Original', 'Garansi resmi',             'ceklis.webp'],
            ['Pengiriman Cepat',      '1x24 jam setelah bayar',    'truck.webp'],
            ['Gratis Ongkir',         'Min. pembelian Rp. 1 juta', 'diskon.webp'],
            ['Harga Terbaik',         'Promo & diskon spesial',    'harga.webp'],
            ['Garansi 7 Hari',        'Jika produk tidak sesuai',  'sheild.webp'],
            ['Layanan 24 Jam',        'Via whatsapp & Live chat',  'clock.webp'],
        ];
        foreach ($rows as $i => [$title, $desc, $icon]) {
            Benefits::updateOrCreate(
                ['title' => $title],
                ['description' => $desc, 'icon_path' => $A.$icon, 'sort_order' => $i + 1, 'is_active' => true]
            );
        }
    }
}
