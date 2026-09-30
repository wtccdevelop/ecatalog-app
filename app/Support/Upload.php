<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class Upload
{
    public static function store(UploadedFile $file, string $dir): string
    {
        $name = Str::uuid().'.'.$file->extension();
        $file->move(public_path($dir), $name);

        return $dir.'/'.$name;
    }

    // hanya hapus file hasil upload admin, bukan aset bawaan seeder
    public static function delete(?string $path, string $dir): void
    {
        if ($path && str_starts_with($path, $dir.'/')) {
            @unlink(public_path($path));
        }
    }
}