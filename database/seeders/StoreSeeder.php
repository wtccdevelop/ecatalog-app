<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stores;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        Stores::updateOrCreate(
            ['name' => 'WTC Cell Jajag'],
            [
                'address'    => 'Jl. PB Sudirman No.65, Dusun Kp. Baru, Jajag, Kec. Gambiran, Kabupaten Banyuwangi, Jawa Timur 68486',
                'maps_url'   => 'https://maps.app.goo.gl/fbkZp636M2WE1Pa37',
                'photo_path' => 'assets/images/wtc.jpg',
                'sort_order' => 1,
                'is_active'  => true,
            ]
        );
    }
}