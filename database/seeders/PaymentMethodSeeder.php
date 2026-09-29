<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentMethods;
class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $A = 'assets/images/';
        $list = [
            ['bca', 'Bank BCA'], ['bankbjb', 'Bank BJB'], ['bni', 'Bank BNI'], ['bri', 'Bank BRI'],
            ['bsi', 'Bank BSI'], ['cimb', 'CIMB Niaga'], ['danamon', 'Bank Danamon'], ['digibank', 'Digibank'],
            ['hsbcc', 'Bank HSBC'], ['jenius', 'Jenius'], ['mandiri', 'Bank Mandiri'], ['maybankt', 'Maybank'],
            ['neobankc', 'Neobank'], ['ocbc', 'OCBC NISP'], ['permatac', 'Bank Permata'],
            ['banksahabatsampoerna', 'Bank Sahabat Sampoerna'], ['uob', 'Bank UOB'], ['shopeepay', 'ShopeePay'],
            ['dana', 'DANA'], ['akulaku', 'Akulaku'], ['kredivoc', 'Kredivo'], ['indodana', 'Indodana'],
        ];
        foreach ($list as $i => [$file, $name]) {
            PaymentMethods::updateOrCreate(
                ['name' => $name],
                ['logo_path' => "{$A}{$file}.png", 'sort_order' => $i + 1, 'is_active' => true]
            );
        }
    }
}
