<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Support\Media;
use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    private const DIR = 'assets/images/products';

    public function index()
    {
        return response()->json(
            Product::with('brand:id,name')
                ->withCount('variants')
                ->withMin('variants', 'price')
                ->orderBy('brand_id')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(fn ($p) => [
                    'id'             => $p->id,
                    'name'           => $p->name,
                    'slug'           => $p->slug,
                    'brand_id'       => $p->brand_id,
                    'brand'          => $p->brand?->name,
                    'image'          => Media::url($p->image_path),
                    'is_active'      => (bool) $p->is_active,
                    'sort_order'     => (int) $p->sort_order,
                    'variants_count' => (int) $p->variants_count,
                    'min_price'      => $p->variants_min_price !== null ? (int) $p->variants_min_price : null,
                ])
                ->values()
        );
    }

    public function show(Product $product)
    {
        return response()->json($this->detail($product));
    }

    public function store(Request $request)
    {
        [$data, $variants, $specs] = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image_path'] = Upload::store($request->file('image'), self::DIR);
        }

        $variantImages = $request->file('variant_images', []);

        $product = DB::transaction(function () use ($data, $variants, $specs, $variantImages) {
            $p = Product::create($data);
            $this->sync($p, $variants, $specs, $variantImages);

            return $p;
        });

        return response()->json($this->detail($product), 201);
    }

    public function update(Request $request, Product $product)
    {
        [$data, $variants, $specs] = $this->validated($request, $product);

        if ($request->hasFile('image')) {
            Upload::delete($product->image_path, self::DIR);
            $data['image_path'] = Upload::store($request->file('image'), self::DIR);
        }

        $variantImages = $request->file('variant_images', []);

        DB::transaction(function () use ($product, $data, $variants, $specs, $variantImages) {
            $product->update($data);
            $this->sync($product, $variants, $specs, $variantImages);
        });

        return response()->json($this->detail($product->refresh()));
    }

    public function destroy(Product $product)
    {
        Upload::delete($product->image_path, self::DIR);

        foreach ($product->images as $img) {
            Upload::delete($img->image_path, ProductImageController::DIR);
        }

        $product->delete(); // varian, spesifikasi, galeri, ulasan ikut terhapus (cascade)

        return response()->json(['ok' => true]);
    }

    public function toggle(Product $product)
    {
        $product->update(['is_active' => ! $product->is_active]);

        return response()->json(['is_active' => (bool) $product->is_active]);
    }

    private function validated(Request $request, ?Product $product = null): array
    {
        $request->merge(['slug' => Str::slug((string) $request->input('name'))]);

        $request->validate([
            'brand_id'              => ['required', 'exists:brands,id'],
            'name'                  => ['required', 'string', 'max:150'],
            'slug'                  => ['required', 'string', 'max:170', Rule::unique('products', 'slug')->ignore($product?->id)],
            'description'           => ['nullable', 'string', 'max:5000'],
            'sort_order'            => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_active'             => ['nullable', 'boolean'],
            'image'                 => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:3072'],
            'variant_images'        => ['nullable', 'array'],
            'variant_images.*'      => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:3072'],
        ], [
            'slug.unique'              => 'Nama produk sudah dipakai.',
            'brand_id.required'        => 'Pilih brand terlebih dahulu.',
            'brand_id.exists'          => 'Brand tidak valid.',
            'variant_images.*.image'   => 'Gambar varian harus berupa file gambar.',
            'variant_images.*.mimes'   => 'Gambar varian harus PNG, JPG, atau WEBP.',
            'variant_images.*.max'     => 'Gambar varian maks. 3 MB.',
        ]);

        // varian & spesifikasi dikirim sebagai JSON string di dalam FormData
        $variants = json_decode((string) $request->input('variants'), true);
        $specs    = json_decode((string) $request->input('specs', '[]'), true) ?? [];

        Validator::make(
            ['variants' => $variants, 'specs' => $specs],
            [
                'variants'              => ['required', 'array', 'min:1'],
                'variants.*.ram'        => ['nullable', 'integer', 'min:1', 'max:65535'],
                'variants.*.storage'    => ['nullable', 'integer', 'min:1', 'max:65535'],
                'variants.*.color'      => ['nullable', 'string', 'max:50'],
                'variants.*.price'      => ['required', 'integer', 'min:0'],
                'variants.*.stock'      => ['required', 'integer', 'min:0'],
                'variants.*.is_active'  => ['boolean'],
                'specs.*.label'         => ['required', 'string', 'max:100'],
                'specs.*.value'         => ['nullable', 'string', 'max:500'],
            ],
            [
                'variants.required'        => 'Minimal harus ada 1 varian.',
                'variants.min'             => 'Minimal harus ada 1 varian.',
                'variants.*.price.required' => 'Harga varian wajib diisi.',
                'variants.*.price.integer'  => 'Harga varian harus berupa angka.',
                'variants.*.stock.required' => 'Stok varian wajib diisi.',
                'variants.*.stock.integer'  => 'Stok varian harus berupa angka.',
                'variants.*.ram.integer'    => 'RAM harus berupa angka.',
                'variants.*.storage.integer'=> 'Storage harus berupa angka.',
                'specs.*.label.required'    => 'Label spesifikasi wajib diisi.',
            ]
        )->validate();

        $data = [
            'brand_id'    => (int) $request->input('brand_id'),
            'name'        => $request->input('name'),
            'slug'        => $request->input('slug'),
            'description' => $request->input('description'),
            'sort_order'  => (int) $request->input('sort_order', 0),
            'is_active'   => $request->boolean('is_active'),
        ];

        return [$data, $variants, $specs];
    }

    private function sync(Product $p, array $variants, array $specs, array $variantImages = []): void
    {
        // peta stok & gambar lama: kunci ram|storage|color
        $old = $p->variants()->get()->mapWithKeys(fn ($v) => [
            $v->ram.'|'.$v->storage.'|'.mb_strtolower((string) $v->color) => $v,
        ]);

        $p->variants()->delete();

        foreach ($variants as $i => $v) {
            $key   = ($v['ram'] ?? null).'|'.($v['storage'] ?? null).'|'.mb_strtolower((string) ($v['color'] ?? ''));
            $prev  = $old->get($key);
            $stock = (int) $v['stock'];

            // upload gambar baru bila ada, hapus lama bila diganti
            $imagePath = $prev?->image_path;
            if (isset($variantImages[$i]) && $variantImages[$i] instanceof \Illuminate\Http\UploadedFile) {
                if ($imagePath) {
                    Upload::delete($imagePath, self::DIR);
                }
                $imagePath = Upload::store($variantImages[$i], self::DIR);
            }

            $p->variants()->create([
                'ram'        => $v['ram'] ?? null,
                'storage'    => $v['storage'] ?? null,
                'color'      => $v['color'] ?? null,
                'image_path' => $imagePath,
                'price'      => (int) $v['price'],
                'stock'      => $stock,
                'is_active'  => (bool) ($v['is_active'] ?? true),
                // pertahankan tanggal lama bila stok tidak berubah
                'stock_updated_at' => ($prev && (int) $prev->stock === $stock)
                    ? $prev->stock_updated_at
                    : now(),
            ]);
        }

        $p->specs()->delete();
        foreach (array_values($specs) as $i => $s) {
            $p->specs()->create([
                'label'      => $s['label'],
                'value'      => $s['value'] ?? null,
                'sort_order' => $i + 1,
            ]);
        }
    }

    private function detail(Product $p): array
    {
        $p->load([
            'variants' => fn ($q) => $q->orderBy('ram')->orderBy('storage')->orderBy('id'),
            'specs',
            'images',
            'reviews' => fn ($q) => $q->latest(),
        ]);

        return [
            'id'          => $p->id,
            'name'        => $p->name,
            'slug'        => $p->slug,
            'brand_id'    => $p->brand_id,
            'description' => $p->description,
            'sort_order'  => (int) $p->sort_order,
            'is_active'   => (bool) $p->is_active,
            'image'       => Media::url($p->image_path),
            'variants'    => $p->variants->map(fn ($v) => [
                'id'        => $v->id,
                'ram'       => $v->ram,
                'storage'   => $v->storage,
                'color'     => $v->color,
                'image'     => Media::url($v->image_path),
                'price'     => (int) $v->price,
                'stock'     => (int) $v->stock,
                'is_active' => (bool) $v->is_active,
            ])->values(),
            'specs'   => $p->specs->map(fn ($s) => ['label' => $s->label, 'value' => $s->value])->values(),
            'gallery' => $p->images->map(fn ($i) => ['id' => $i->id, 'url' => Media::url($i->image_path)])->values(),
            'reviews' => $p->reviews->map(fn ($r) => [
                'id'        => $r->id,
                'name'      => $r->name,
                'rating'    => (int) $r->rating,
                'comment'   => $r->comment,
                'is_active' => (bool) $r->is_active,
                'date'      => $r->created_at->format('d M Y'),
            ])->values(),
        ];
    }
}