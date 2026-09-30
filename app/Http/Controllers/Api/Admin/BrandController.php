<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Support\Media;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{
    private const DIR = 'assets/images/brands';

    public function index()
    {
        return response()->json(
            Brand::withCount('products')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(fn ($b) => $this->map($b))
                ->values()
        );
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $this->upload($request->file('logo'));
        }

        $brand = Brand::create($data);

        return response()->json($this->map($brand->loadCount('products')), 201);
    }

    public function update(Request $request, Brand $brand)
    {
        $data = $this->validated($request, $brand);

        if ($request->hasFile('logo')) {
            $this->deleteFile($brand->logo_path);
            $data['logo_path'] = $this->upload($request->file('logo'));
        }

        $brand->update($data);

        return response()->json($this->map($brand->loadCount('products')));
    }

    public function destroy(Brand $brand)
    {
        $this->deleteFile($brand->logo_path);
        $brand->delete(); // produk ikut terhapus (cascade)

        return response()->json(['ok' => true]);
    }

    private function validated(Request $request, ?Brand $brand = null): array
    {
        $request->merge(['slug' => Str::slug((string) $request->input('name'))]);

        $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'slug'       => ['required', 'string', 'max:120', Rule::unique('brands', 'slug')->ignore($brand?->id)],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_active'  => ['nullable', 'boolean'],
            'logo'       => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
        ], [
            'slug.unique' => 'Nama brand sudah dipakai.',
        ]);

        return [
            'name'       => $request->input('name'),
            'slug'       => $request->input('slug'),
            'sort_order' => (int) $request->input('sort_order', 0),
            'is_active'  => $request->boolean('is_active'),
        ];
    }

    private function upload(UploadedFile $file): string
    {
        $name = Str::uuid().'.'.$file->extension();
        $file->move(public_path(self::DIR), $name);

        return self::DIR.'/'.$name;
    }

    // hanya hapus file hasil upload admin, bukan aset bawaan
    private function deleteFile(?string $path): void
    {
        if ($path && str_starts_with($path, self::DIR.'/')) {
            @unlink(public_path($path));
        }
    }

    private function map(Brand $b): array
    {
        return [
            'id'             => $b->id,
            'name'           => $b->name,
            'slug'           => $b->slug,
            'logo'           => Media::url($b->logo_path),
            'sort_order'     => (int) $b->sort_order,
            'is_active'      => (bool) $b->is_active,
            'products_count' => (int) ($b->products_count ?? 0),
        ];
    }
}