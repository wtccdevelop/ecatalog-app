<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Benefits;
use App\Support\Media;
use App\Support\Upload;
use Illuminate\Http\Request;

class BenefitController extends Controller
{
    private const DIR = 'assets/images/benefits';

    public function index()
    {
        return response()->json(
            Benefits::orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(fn ($b) => $this->map($b))
                ->values()
        );
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('icon')) {
            $data['icon_path'] = Upload::store($request->file('icon'), self::DIR);
        }

        return response()->json($this->map(Benefits::create($data)), 201);
    }

    public function update(Request $request, Benefits $benefit)
    {
        $data = $this->validated($request);

        if ($request->hasFile('icon')) {
            Upload::delete($benefit->icon_path, self::DIR);
            $data['icon_path'] = Upload::store($request->file('icon'), self::DIR);
        }

        $benefit->update($data);

        return response()->json($this->map($benefit->refresh()));
    }

    public function destroy(Benefits $benefit)
    {
        Upload::delete($benefit->icon_path, self::DIR);
        $benefit->delete();

        return response()->json(['ok' => true]);
    }

    public function toggle(Benefits $benefit)
    {
        $benefit->update(['is_active' => ! $benefit->is_active]);

        return response()->json(['is_active' => (bool) $benefit->is_active]);
    }

    private function validated(Request $request): array
    {
        $request->validate([
            'title'       => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'sort_order'  => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_active'   => ['nullable', 'boolean'],
            'icon'        => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp,svg', 'max:1024'],
        ], [
            'title.required' => 'Judul benefit wajib diisi.',
            'icon.image'     => 'File harus berupa gambar.',
            'icon.max'       => 'Ukuran ikon maksimal 1 MB.',
        ]);

        return [
            'title'       => $request->input('title'),
            'description' => $request->input('description') ?: null,
            'sort_order'  => (int) $request->input('sort_order', 0),
            'is_active'   => $request->boolean('is_active'),
        ];
    }

    private function map(Benefits $b): array
    {
        return [
            'id'          => $b->id,
            'title'       => $b->title,
            'description' => $b->description,
            'icon'        => Media::url($b->icon_path),
            'sort_order'  => (int) $b->sort_order,
            'is_active'   => (bool) $b->is_active,
        ];
    }
}