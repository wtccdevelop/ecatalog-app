<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Settings;
class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = [
            'wa_number'       => '6281234567890', // ganti nomor WA asli
            'operating_hours' => 'Senin – Minggu: 08.00 – 21.00 WITA',
            'about_1'         => 'WTC Cell adalah toko gadget & smartphone terpercaya di Banyuwangi. Berkomitmen memberikan produk 100% original dengan harga terbaik dan pelayanan prima.',
            'about_2'         => 'Kami hadir dengan berbagai brand ternama: Apple, Samsung, Oppo, Vivo, Xiaomi, Realme, Infinix, dan Tecno. Tersedia pilihan pembelian tunai maupun kredit dengan cicilan ringan.',
        ];
        foreach ($rows as $key => $value) {
            Settings::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
