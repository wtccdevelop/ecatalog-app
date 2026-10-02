<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Support\Media;
use Illuminate\Http\Request;
use App\Models\PriceCategories;

class ProductController extends Controller
{
        public function index(Request $request)
    {
        $q = Product::query()
            ->where('is_active', true)
            ->whereHas('brand', fn ($b) => $b->where('is_active', true))
            ->whereHas('variants', fn ($v) => $v->where('is_active', true))
            ->with('brand:id,name,slug')
            ->withMin(['variants as min_price' => fn ($v) => $v->where('is_active', true)], 'price');

        // brand (slug)
        if ($brand = trim((string) $request->query('brand', ''))) {
            $q->whereHas('brand', fn ($b) => $b->where('slug', $brand));
        }

        // kategori harga (id) -> filter berdasarkan harga mulai dari
        if ($catId = (int) $request->query('price', 0)) {
            $cat = PriceCategories::where('is_active', true)->find($catId);

            if ($cat) {
                if ($cat->min_price !== null) {
                    $q->having('min_price', '>=', $cat->min_price);
                }
                if ($cat->max_price !== null) {
                    $q->having('min_price', '<=', $cat->max_price);
                }
            }
        }

        // pencarian nama
        if (($kw = trim((string) $request->query('q', ''))) !== '') {
            $q->where('name', 'like', '%'.addcslashes(mb_substr($kw, 0, 100), '%_\\').'%');
        }

        match ($request->query('sort')) {
            'price_asc'  => $q->orderBy('min_price')->orderBy('id'),
            'price_desc' => $q->orderByDesc('min_price')->orderBy('id'),
            'name'       => $q->orderBy('name')->orderBy('id'),
            default      => $q->orderBy('brand_id')->orderBy('sort_order')->orderBy('id'),
        };

        $page = $q->paginate(12);

        return response()->json([
            'data' => $page->getCollection()->map(fn ($p) => [
                'id'    => $p->id,
                'slug'  => $p->slug,
                'name'  => $p->name,
                'brand' => $p->brand?->name,
                'image' => Media::url($p->image_path),
                'price' => (int) $p->min_price,
            ])->values(),
            'meta' => [
                'current_page' => $page->currentPage(),
                'last_page'    => $page->lastPage(),
                'total'        => $page->total(),
            ],
        ]);
    }
    
    public function search(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $like = '%'.addcslashes($q, '%_\\').'%';

        $results = Product::where('is_active', true)
            ->where('name', 'like', $like)
            ->with(['variants' => fn ($v) => $v->where('is_active', true)])
            ->orderBy('sort_order')
            ->limit(8)
            ->get()
            ->map(fn ($p) => [
                'id'    => $p->id,
                'slug'  => $p->slug,
                'name'  => $p->name,
                'image' => Media::url($p->image_path),
                'price' => (int) $p->variants->min('price'),
            ]);

        return response()->json($results);
    }

    public function show(string $slug)
    {
        $p = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with([
                'brand',
                'variants' => fn ($q) => $q->where('is_active', true)
                    ->orderBy('ram')->orderBy('storage')->orderBy('id'),
                'specs',
                'images',
                'reviews' => fn ($q) => $q->where('is_active', true)->latest(),
            ])
            ->firstOrFail();

        return response()->json([
            'id'          => $p->id,
            'name'        => $p->name,
            'slug'        => $p->slug,
            'brand'       => $p->brand->name,
            'image'       => Media::url($p->image_path),
            'description' => $p->description,
            'variants'    => $p->variants->map(fn ($v) => [
                'id'      => $v->id,
                'ram'     => $v->ram,
                'storage' => $v->storage,
                'color'   => $v->color,
                'price'   => (int) $v->price,
                'stock'   => (int) $v->stock,
                'stock_updated_at' => $v->stock_updated_at?->toIso8601String(),
            ])->values(),
            'specs'   => $p->specs->map(fn ($s) => ['label' => $s->label, 'value' => $s->value])->values(),
            'gallery' => $p->images->map(fn ($i) => Media::url($i->image_path))->values(),
            'reviews' => $p->reviews->map(fn ($r) => [
                'id'      => $r->id,
                'name'    => $r->name,
                'rating'  => (int) $r->rating,
                'comment' => $r->comment,
                'date'    => $r->created_at->format('d M Y'),
            ])->values(),
        ]);
    }
}