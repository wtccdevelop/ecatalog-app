<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Support\Media;
use App\Support\Upload;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    private const DIR = 'assets/images/banners';

    public function index()
    {
        return response()->json(
            Banner::orderBy('type')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(fn ($b) => $this->map($b))
                ->values()
        );
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);
        $data['image_path'] = Upload::store($request->file('image'), self::DIR);

        $banner = Banner::create($data);

        return response()->json($this->map($banner), 201);
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            Upload::delete($banner->image_path, self::DIR);
            $data['image_path'] = Upload::store($request->file('image'), self::DIR);
        }

        $banner->update($data);

        return response()->json($this->map($banner->refresh()));
    }

    public function destroy(Banner $banner)
    {
        Upload::delete($banner->image_path, self::DIR);
        $banner->delete();

        return response()->json(['ok' => true]);
    }

    public function toggle(Banner $banner)
    {
        $banner->update(['is_active' => ! $banner->is_active]);

        return response()->json($this->map($banner->refresh()));
    }

    private function validated(Request $request, bool $creating = false): array
    {
        $request->validate([
            'type'       => ['required', 'in:main,small'],
            'title'      => ['nullable', 'string', 'max:255'],
            'link_url'   => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_active'  => ['nullable', 'boolean'],
            'starts_at'  => ['nullable', 'date'],
            'ends_at'    => ['nullable', 'date', 'after_or_equal:starts_at'],
            'image'      => [$creating ? 'required' : 'nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:3072'],
        ], [
            'image.required'        => 'Gambar banner wajib diunggah.',
            'image.image'           => 'File harus berupa gambar.',
            'image.max'             => 'Ukuran gambar maksimal 3 MB.',
            'ends_at.after_or_equal' => 'Tanggal berakhir harus setelah tanggal mulai.',
        ]);

        return [
            'type'       => $request->input('type'),
            'title'      => $request->input('title') ?: null,
            'link_url'   => $request->input('link_url') ?: null,
            'sort_order' => (int) $request->input('sort_order', 0),
            'is_active'  => $request->boolean('is_active'),
            'starts_at'  => $request->input('starts_at') ?: null,
            'ends_at'    => $request->input('ends_at') ?: null,
        ];
    }

    private function map(Banner $b): array
    {
        $now = now();

        $state = match (true) {
            ! $b->is_active                          => 'inactive',
            $b->starts_at && $b->starts_at->gt($now) => 'scheduled',
            $b->ends_at && $b->ends_at->lt($now)     => 'expired',
            default                                  => 'live',
        };

        return [
            'id'         => $b->id,
            'type'       => $b->type,
            'title'      => $b->title,
            'image'      => Media::url($b->image_path),
            'link_url'   => $b->link_url,
            'sort_order' => (int) $b->sort_order,
            'is_active'  => (bool) $b->is_active,
            'starts_at'  => $b->starts_at?->format('Y-m-d\TH:i'),
            'ends_at'    => $b->ends_at?->format('Y-m-d\TH:i'),
            'state'      => $state,
        ];
    }
}