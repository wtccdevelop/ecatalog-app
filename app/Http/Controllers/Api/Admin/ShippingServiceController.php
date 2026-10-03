<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingService;
use App\Support\Media;
use App\Support\Upload;
use Illuminate\Http\Request;

class ShippingServiceController extends Controller
{
    private const DIR = 'assets/images/shipping';

    public function index()
    {
        return response()->json(
            ShippingService::orderBy('sort_order')->orderBy('id')->get()
                ->map(fn ($s) => $this->map($s))->values()
        );
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);
        $data['logo_path'] = Upload::store($request->file('logo'), self::DIR);

        return response()->json($this->map(ShippingService::create($data)), 201);
    }

    public function update(Request $request, ShippingService $shipping)
    {
        $data = $this->validated($request);

        if ($request->hasFile('logo')) {
            Upload::delete($shipping->logo_path, self::DIR);
            $data['logo_path'] = Upload::store($request->file('logo'), self::DIR);
        }

        $shipping->update($data);

        return response()->json($this->map($shipping->refresh()));
    }

    public function destroy(ShippingService $shipping)
    {
        Upload::delete($shipping->logo_path, self::DIR);
        $shipping->delete();

        return response()->json(['ok' => true]);
    }

    public function toggle(ShippingService $shipping)
    {
        $shipping->update(['is_active' => ! $shipping->is_active]);

        return response()->json(['is_active' => (bool) $shipping->is_active]);
    }

    private function validated(Request $request, bool $creating = false): array
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_active'  => ['nullable', 'boolean'],
            'logo'       => [$creating ? 'required' : 'nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:1024'],
        ], [
            'name.required' => 'Nama jasa kirim wajib diisi.',
            'logo.required' => 'Logo wajib diunggah.',
            'logo.image'    => 'File harus berupa gambar.',
            'logo.max'      => 'Ukuran logo maksimal 1 MB.',
        ]);

        return [
            'name'       => $request->input('name'),
            'sort_order' => (int) $request->input('sort_order', 0),
            'is_active'  => $request->boolean('is_active'),
        ];
    }

    private function map(ShippingService $s): array
    {
        return [
            'id'         => $s->id,
            'name'       => $s->name,
            'logo'       => Media::url($s->logo_path),
            'sort_order' => (int) $s->sort_order,
            'is_active'  => (bool) $s->is_active,
        ];
    }
}