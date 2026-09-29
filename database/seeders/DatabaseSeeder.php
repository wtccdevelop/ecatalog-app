<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguard();

        $this->call([
            BrandSeeder::class,
            PriceCategorySeeder::class,
            BannerSeeder::class,
            ProductSeeder::class,   // harus setelah BrandSeeder
            StoreSeeder::class,
            SocialLinkSeeder::class,
            BenefitSeeder::class,
            PaymentMethodSeeder::class,
            SettingSeeder::class,
        ]);

        User::updateOrCreate(
            ['email' => 'admin@wtccell.test'],
            ['name' => 'Admin', 'password' => Hash::make('root')]
        );
    }
}