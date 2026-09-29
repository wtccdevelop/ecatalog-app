<?php

namespace App\Support;

class Media
{
    public static function url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (preg_match('#^(https?:)?//#', $path)) {
            return $path;
        }

        return '/'.ltrim($path, '/');
    }
}