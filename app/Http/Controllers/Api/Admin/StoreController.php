<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stores;
use App\Support\Media;
use App\Support\Upload;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    private const DIR = 'assets/images/stores';

    public function index()
    {
        return response()->json(
            Stores::orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(fn ($s) => $this->map($s))
                ->values()
        );
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('photo')) {
            $data['photo_path'] = Upload::store($request->file('photo'), self::DIR);
        }

        return response()->json($this->map(Stores::create($data)), 201);
    }

    public function update(Request $request, Stores $store)
    {
        $data = $this->validated($request);

        if ($request->hasFile('photo')) {
            Upload::delete($store->photo_path, self::DIR);
            $data['photo_path'] = Upload::store($request->file('photo'), self::DIR);
        }

        $store->update($data);

        return response()->json($this->map($store->refresh()));
    }

    public function destroy(Stores $store)
    {
        Upload::delete($store->photo_path, self::DIR);
        $store->delete();

        return response()->json(['ok' => true]);
    }

    public function toggle(Stores $store)
    {
        $store->update(['is_active' => ! $store->is_active]);

        return response()->json(['is_active' => (bool) $store->is_active]);
    }

    private function validated(Request $request): array
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:150'],
            'address'    => ['nullable', 'string', 'max:1000'],
            'maps_url'   => ['nullable', 'url', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_active'  => ['nullable', 'boolean'],
            'photo'      => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:3072'],
        ], [
            'name.required'  => 'Nama toko wajib diisi.',
            'maps_url.url'   => 'Link Google Maps harus berupa URL yang valid (diawali https://).',
            'photo.image'    => 'File harus berupa gambar.',
            'photo.max'      => 'Ukuran foto maksimal 3 MB.',
        ]);

        return [
            'name'       => $request->input('name'),
            'address'    => $request->input('address') ?: null,
            'maps_url'   => $request->input('maps_url') ?: null,
            'sort_order' => (int) $request->input('sort_order', 0),
            'is_active'  => $request->boolean('is_active'),
        ];
    }

    private function map(Stores $s): array
    {
        return [
            'id'         => $s->id,
            'name'       => $s->name,
            'address'    => $s->address,
            'maps_url'   => $s->maps_url,
            'photo'      => Media::url($s->photo_path),
            'sort_order' => (int) $s->sort_order,
            'is_active'  => (bool) $s->is_active,
        ];
    }
}