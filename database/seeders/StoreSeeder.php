<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Stores;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    
    public function run(): void
    {
        Stores::updateOrCreate(
            ['title' => 'WTC Cell Jajag'],
            [
                'address'    => 'Jl. PB Sudirman No.65, Dusun Kp. Baru, Jajag, Kec. Gambiran, Kabupaten Banyuwangi, Jawa Timur 68486',
                'maps_url'   => 'https://maps.app.goo.gl/fbkZp636M2WE1Pa37',
                'image_path' => 'assets/images/wtc.jpg',
                'sort_order' => 1,
                'is_active'  => true,
            ]
        );
    }
}
