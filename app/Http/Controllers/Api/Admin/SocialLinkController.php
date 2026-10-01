<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class SocialLinkController extends Controller
{
    // hanya platform yang punya ikon di resources/js/data/socialIcons.js
    private const PLATFORMS = ['instagram', 'facebook', 'tiktok'];

    public function index()
    {
        return response()->json(
            SocialLink::orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(fn ($s) => $this->map($s))
                ->values()
        );
    }

    public function store(Request $request)
    {
        return response()->json($this->map(SocialLink::create($this->validated($request))), 201);
    }

    public function update(Request $request, SocialLink $social)
    {
        $social->update($this->validated($request));

        return response()->json($this->map($social->refresh()));
    }

    public function destroy(SocialLink $social)
    {
        $social->delete();

        return response()->json(['ok' => true]);
    }

    public function toggle(SocialLink $social)
    {
        $social->update(['is_active' => ! $social->is_active]);

        return response()->json(['is_active' => (bool) $social->is_active]);
    }

    private function validated(Request $request): array
    {
        $request->validate([
            'platform'   => ['required', 'in:'.implode(',', self::PLATFORMS)],
            'label'      => ['nullable', 'string', 'max:255'],
            'handle'     => ['nullable', 'string', 'max:255'],
            'url'        => ['required', 'url', 'max:500', 'starts_with:https://,http://'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_active'  => ['nullable', 'boolean'],
        ], [
            'platform.required' => 'Pilih platform.',
            'platform.in'       => 'Platform tidak valid.',
            'url.required'      => 'Link wajib diisi.',
            'url.url'           => 'Link harus berupa URL yang valid (diawali https://).',
        ]);

        return [
            'platform'   => $request->input('platform'),
            'label'      => $request->input('label') ?: null,
            'handle'     => $request->input('handle') ?: null,
            'url'        => $request->input('url'),
            'sort_order' => (int) $request->input('sort_order', 0),
            'is_active'  => $request->boolean('is_active'),
        ];
    }

    private function map(SocialLink $s): array
    {
        return [
            'id'         => $s->id,
            'platform'   => $s->platform,
            'label'      => $s->label,
            'handle'     => $s->handle,
            'url'        => $s->url,
            'sort_order' => (int) $s->sort_order,
            'is_active'  => (bool) $s->is_active,
        ];
    }
}