<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create {email}';

    protected $description = 'Buat atau perbarui akun admin';

    public function handle(): int
    {
        $email = $this->argument('email');

        $password = $this->secret('Password (min. 12 karakter, huruf besar/kecil + angka)');
        $confirm  = $this->secret('Ulangi password');

        if ($password !== $confirm) {
            $this->error('Password tidak sama.');

            return self::FAILURE;
        }

        $validator = Validator::make(
            ['email' => $email, 'password' => $password],
            [
                'email'    => ['required', 'email'],
                'password' => ['required', Password::min(12)->mixedCase()->numbers()],
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

            return self::FAILURE;
        }

        User::updateOrCreate(
            ['email' => $email],
            ['name' => 'Admin', 'password' => Hash::make($password)]
        );

        $this->info("Akun admin {$email} siap dipakai.");

        return self::SUCCESS;
    }
}