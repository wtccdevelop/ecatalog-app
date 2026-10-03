<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Benefits;
use App\Models\Brand;
use App\Models\PaymentMethods;
use App\Models\PriceCategories;
use App\Models\Stores;
use App\Support\Media;
use App\Models\ShippingService;
class HomeController extends Controller
{
    public function __invoke()
    {
        $now = now();

        $banners = Banner::where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now))
            ->orderBy('sort_order')
            ->get();

        $bannerMap = fn ($b) => [
            'id'    => $b->id,
            'title' => $b->title,
            'image' => Media::url($b->image_path),
            'link'  => $b->link_url,
        ];

        $brands = Brand::where('is_active', true)
            ->orderBy('sort_order')
            ->with([
                'products' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
                'products.variants' => fn ($q) => $q->where('is_active', true),
            ])
            ->get();

        return response()->json([
            'banners'       => $banners->where('type', 'main')->map($bannerMap)->values(),
            'small_banners' => $banners->where('type', 'small')->map($bannerMap)->values(),

            'brands' => $brands->map(fn ($b) => [
                'id'   => $b->id,
                'name' => $b->name,
                'slug' => $b->slug,
                'logo' => Media::url($b->logo_path),
            ])->values(),

            'price_categories' => PriceCategories::where('is_active', true)
                ->orderBy('sort_order')
                ->get(['id', 'name', 'min_price', 'max_price']),

            'benefits' => Benefits::where('is_active', true)
                ->orderBy('sort_order')
                ->get()
                ->map(fn ($b) => [
                    'title'       => $b->title,
                    'description' => $b->description,
                    'icon'        => Media::url($b->icon_path),
                ])->values(),

            'stores' => Stores::where('is_active', true)
                ->orderBy('sort_order')
                ->get()
                ->map(fn ($s) => [
                    'id'       => $s->id,
                    'name'     => $s->name,
                    'address'  => $s->address,
                    'maps_url' => $s->maps_url,
                    'photo'    => Media::url($s->photo_path),
                ])->values(),

            'payments' => PaymentMethods::where('is_active', true)
                ->orderBy('sort_order')
                ->get()
                ->map(fn ($p) => [
                    'name' => $p->name,
                    'logo' => Media::url($p->logo_path),
                ])->values(),
            
            'shipping' => ShippingService::where('is_active', true)
                ->orderBy('sort_order')
                ->get()
                ->map(fn ($s) => [
                    'name' => $s->name,
                    'logo' => Media::url($s->logo_path),
                ])->values(),

            'products_by_brand' => $brands
                ->map(fn ($b) => [
                    'brand'    => $b->name,
                    'slug'     => $b->slug,
                    'products' => $b->products->map(function ($p) {
                        $price = $p->variants->min('price');

                        return $price === null ? null : [
                            'id'    => $p->id,
                            'slug'  => $p->slug,
                            'name'  => $p->name,
                            'image' => Media::url($p->image_path),
                            'price' => (int) $price, // harga terendah dari varian aktif
                        ];
                    })->filter()->values(),
                ])
                ->filter(fn ($g) => $g['products']->isNotEmpty())
                ->values(),
        ]);
    }
}