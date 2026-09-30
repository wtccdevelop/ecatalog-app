<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImages;
use App\Support\Media;
use App\Support\Upload;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    public const DIR = 'assets/images/products/gallery';

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'images'   => ['required', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:png,jpg,jpeg,webp', 'max:3072'],
        ], [
            'images.required' => 'Pilih minimal 1 gambar.',
            'images.*.image'  => 'File harus berupa gambar.',
            'images.*.max'    => 'Ukuran gambar maksimal 3 MB.',
        ]);

        $next = (int) ProductImages::where('product_id', $product->id)->max('sort_order');

        foreach ($request->file('images') as $file) {
            $next++;
            $product->images()->create([
                'image_path' => Upload::store($file, self::DIR),
                'sort_order' => $next,
            ]);
        }

        return response()->json($this->list($product));
    }

    public function destroy(Product $product, ProductImages $image)
    {
        abort_if($image->product_id !== $product->id, 404);

        Upload::delete($image->image_path, self::DIR);
        $image->delete();

        return response()->json(['ok' => true]);
    }

    private function list(Product $product)
    {
        return ProductImages::where('product_id', $product->id)
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($i) => ['id' => $i->id, 'url' => Media::url($i->image_path)])
            ->values();
    }
}