<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SocialLink;

class SocialLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = [
            ['instagram', 'Syihab Banjarbaru', '@syihab_banjarbaru', 'https://www.instagram.com/syihab_banjarbaru/'],
            ['facebook',  'Facebook',          null, 'https://www.facebook.com/people/Syihab-Store-Banjarbaru/100092506301237/'],
            ['tiktok',    'TikTok',            null, 'https://www.tiktok.com/@syihabstore_'],
        ];
        foreach ($rows as $i => [$platform, $label, $handle, $url]) {
            SocialLink::updateOrCreate(
                ['url' => $url],
                compact('platform', 'label', 'handle') + ['sort_order' => $i + 1, 'is_active' => true]
            );
        }
    }
}
