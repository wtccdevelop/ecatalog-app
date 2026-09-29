<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Support\Media;
use Illuminate\Http\Request;

class ProductController extends Controller
{
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