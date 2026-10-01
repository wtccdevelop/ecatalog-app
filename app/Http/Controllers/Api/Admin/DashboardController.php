<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Settings;
use App\Models\Stores;
use App\Support\Stats;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private const LOW_STOCK = 3;

    public function __invoke()
    {
        $today     = today()->toDateString();
        $yesterday = today()->subDay()->toDateString();

        $now   = $this->day($today);
        $prev  = $this->day($yesterday);
        $week  = new Stats(today()->subDays(6)->toDateString(), $today);

        return response()->json([
            'counts' => [
                'brands'   => Brand::count(),
                'products' => Product::count(),
                'banners'  => Banner::count(),
                'stores'   => Stores::count(),
            ],
            'today'     => $now,
            'yesterday' => $prev,
            'online_now' => $week->summary()['online_now'],
            'attention' => $this->attention(),
            'top_products' => array_slice($week->products(), 0, 5),
            'top_keywords' => array_slice($week->keywords(), 0, 5),
        ]);
    }

    private function day(string $date): array
    {
        $c = DB::table('page_views')->where('visit_date', $date)->selectRaw("
            SUM(CASE WHEN event = 'pageview' THEN 1 ELSE 0 END) AS pv,
            SUM(CASE WHEN event = 'wa_click' THEN 1 ELSE 0 END) AS wa,
            SUM(CASE WHEN event = 'installment_view' THEN 1 ELSE 0 END) AS ins")->first();

        return [
            'visitors'   => DB::table('visitor_days')->where('visit_date', $date)->count(),
            'page_views' => (int) ($c->pv ?? 0),
            'wa_clicks'  => (int) ($c->wa ?? 0),
            'installments' => (int) ($c->ins ?? 0),
        ];
    }

    // daftar "perlu perhatian": tiap item = { level, text, to }
    private function attention(): array
    {
        $items = [];
        $add = function (string $level, string $text, string $to) use (&$items) {
            $items[] = compact('level', 'text', 'to');
        };

        // nomor WA masih contoh / belum diisi
        $wa = (string) Settings::where('key', 'wa_number')->value('value');
        if ($wa === '' || $wa === '6281234567890') {
            $add('danger', 'Nomor WhatsApp masih nomor contoh. Tombol "Pesan via WhatsApp" belum mengarah ke toko Anda.', '/admin/settings');
        }

        // stok habis / menipis (hanya varian & produk aktif)
        $variants = ProductVariant::where('is_active', true)
            ->whereHas('product', fn ($p) => $p->where('is_active', true));

        $out = (clone $variants)->where('stock', 0)->count();
        if ($out) {
            $add('danger', "{$out} varian stok habis tetapi masih tampil di website.", '/admin/products');
        }

        $low = (clone $variants)->whereBetween('stock', [1, self::LOW_STOCK])->count();
        if ($low) {
            $add('warning', "{$low} varian stok menipis (≤ ".self::LOW_STOCK.').', '/admin/products');
        }

        // banner
        $now = now();
        $expired = Banner::where('is_active', true)->whereNotNull('ends_at')->where('ends_at', '<', $now)->count();
        if ($expired) {
            $add('warning', "{$expired} banner sudah berakhir tetapi masih berstatus aktif.", '/admin/banners');
        }

        $soon = Banner::where('is_active', true)->whereBetween('ends_at', [$now, $now->copy()->addDays(7)])->count();
        if ($soon) {
            $add('info', "{$soon} banner akan berakhir dalam 7 hari.", '/admin/banners');
        }

        // kelengkapan produk aktif
        $noSpec = Product::where('is_active', true)->whereDoesntHave('specs')->count();
        if ($noSpec) {
            $add('info', "{$noSpec} produk aktif belum punya spesifikasi.", '/admin/products');
        }

        $noGallery = Product::where('is_active', true)->whereDoesntHave('images')->count();
        if ($noGallery) {
            $add('info', "{$noGallery} produk aktif belum punya galeri.", '/admin/products');
        }

        // ulasan disembunyikan
        $hidden = DB::table('product_reviews')->where('is_active', false)->count();
        if ($hidden) {
            $add('info', "{$hidden} ulasan sedang disembunyikan.", '/admin/products');
        }

        // toko aktif tanpa foto (tidak muncul di slider)
        $noPhoto = Stores::where('is_active', true)->whereNull('photo_path')->count();
        if ($noPhoto) {
            $add('info', "{$noPhoto} toko aktif belum punya foto (tidak muncul di slider beranda).", '/admin/stores');
        }

        return $items;
    }
}