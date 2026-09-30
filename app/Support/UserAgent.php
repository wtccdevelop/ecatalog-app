<?php

namespace App\Support;

class UserAgent
{
    public static function parse(string $ua): array
    {
        $isTablet = (bool) preg_match('/iPad|Tablet|SM-[TX]\d|Android(?!.*Mobile)/i', $ua);
        $isMobile = (bool) preg_match('/Mobile|iPhone|Android/i', $ua);

        [$brand, $model] = self::device($ua);

        return [
            'device_type'  => $isTablet ? 'tablet' : ($isMobile ? 'smartphone' : 'desktop'),
            'device_brand' => $brand,
            'device_model' => $model ? mb_substr($model, 0, 100) : null,
            'os'           => self::os($ua),
            'browser'      => self::browser($ua),
        ];
    }

    public static function referrer(?string $ref, string $ownHost): string
    {
        if (! $ref) {
            return 'direct';
        }

        $host = strtolower((string) parse_url($ref, PHP_URL_HOST));

        if ($host === '' || $host === strtolower($ownHost)) {
            return 'direct';
        }

        return match (true) {
            str_contains($host, 'instagram')                          => 'instagram',
            str_contains($host, 'tiktok')                             => 'tiktok',
            str_contains($host, 'facebook'), str_contains($host, 'fb.') => 'facebook',
            str_contains($host, 'whatsapp'), $host === 'wa.me'        => 'whatsapp',
            str_contains($host, 'google')                             => 'google',
            default                                                   => 'other',
        };
    }

    private static function device(string $ua): array
    {
        if (preg_match('/iPhone/i', $ua)) return ['Apple', 'iPhone'];
        if (preg_match('/iPad/i', $ua))   return ['Apple', 'iPad'];

        if (! preg_match('/Android [\d.]+;\s*([^;)]+)/i', $ua, $m)) {
            return [null, null];
        }

        $model = trim((string) preg_replace('/\s+Build\/.*$/i', '', $m[1]));

        // Chrome modern menyembunyikan model HP (User-Agent Reduction)
        if ($model === '' || $model === 'K') {
            return [null, null];
        }

        $brands = [
            'Samsung' => '/^SM-|^GT-|samsung/i',
            'Oppo'    => '/^(CPH|PH[A-Z]M|PG[A-Z]M|PE[A-Z]M|PD[A-Z]M)|OPPO/i',
            'Vivo'    => '/^(V\d{4}|vivo)/i',
            'Realme'  => '/^RMX|realme/i',
            'Xiaomi'  => '/Redmi|Xiaomi|POCO|^Mi |^M\d{4}[A-Z]|^\d{5,}[A-Z0-9]+$/i',
            'Infinix' => '/Infinix|^X\d{3,4}[A-Z]?$/i',
            'Tecno'   => '/TECNO/i',
            'Honor'   => '/HONOR/i',
            'Huawei'  => '/HUAWEI|^[A-Z]{3}-[AL]\d/i',
            'OnePlus' => '/OnePlus|^(IN|LE|KB|HD|GM|NE)\d{4}/i',
            'Google'  => '/Pixel/i',
            'Nokia'   => '/Nokia/i',
            'Asus'    => '/ASUS|^ZS\d|^AI\d{4}/i',
        ];

        foreach ($brands as $brand => $re) {
            if (preg_match($re, $model)) {
                return [$brand, $model];
            }
        }

        return ['Lainnya', $model];
    }

    private static function os(string $ua): ?string
    {
        return match (true) {
            (bool) preg_match('/iPhone|iPad|iPod/i', $ua) => 'iOS',
            (bool) preg_match('/Android (\d+)/i', $ua, $m) => 'Android '.$m[1],
            (bool) preg_match('/Windows NT/i', $ua)        => 'Windows',
            (bool) preg_match('/Mac OS X/i', $ua)          => 'macOS',
            (bool) preg_match('/CrOS/i', $ua)              => 'ChromeOS',
            (bool) preg_match('/Linux/i', $ua)             => 'Linux',
            default                                        => null,
        };
    }

    private static function browser(string $ua): ?string
    {
        $list = [
            'Instagram'        => '/Instagram/i',
            'Facebook'         => '/FBAN|FBAV/i',
            'TikTok'           => '/musical_ly|TikTok|BytedanceWebview/i',
            'Edge'             => '/Edg(e|A|iOS)?\//i',
            'Opera'            => '/OPR\/|Opera/i',
            'Samsung Internet' => '/SamsungBrowser/i',
            'UC Browser'       => '/UCBrowser/i',
            'Firefox'          => '/Firefox|FxiOS/i',
            'Chrome'           => '/Chrome|CriOS/i',
            'Safari'           => '/Safari/i',
        ];

        foreach ($list as $name => $re) {
            if (preg_match($re, $ua)) {
                return $name;
            }
        }

        return null;
    }
}