<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethods;
use App\Support\Media;
use App\Support\Upload;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    private const DIR = 'assets/images/payments';

    public function index()
    {
        return response()->json(
            PaymentMethods::orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(fn ($p) => $this->map($p))
                ->values()
        );
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);
        $data['logo_path'] = Upload::store($request->file('logo'), self::DIR);

        return response()->json($this->map(PaymentMethods::create($data)), 201);
    }

    public function update(Request $request, PaymentMethods $payment)
    {
        $data = $this->validated($request);

        if ($request->hasFile('logo')) {
            Upload::delete($payment->logo_path, self::DIR);
            $data['logo_path'] = Upload::store($request->file('logo'), self::DIR);
        }

        $payment->update($data);

        return response()->json($this->map($payment->refresh()));
    }

    public function destroy(PaymentMethods $payment)
    {
        Upload::delete($payment->logo_path, self::DIR);
        $payment->delete();

        return response()->json(['ok' => true]);
    }

    public function toggle(PaymentMethods $payment)
    {
        $payment->update(['is_active' => ! $payment->is_active]);

        return response()->json(['is_active' => (bool) $payment->is_active]);
    }

    private function validated(Request $request, bool $creating = false): array
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_active'  => ['nullable', 'boolean'],
            'logo'       => [$creating ? 'required' : 'nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:1024'],
        ], [
            'name.required' => 'Nama metode pembayaran wajib diisi.',
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

    private function map(PaymentMethods $p): array
    {
        return [
            'id'         => $p->id,
            'name'       => $p->name,
            'logo'       => Media::url($p->logo_path),
            'sort_order' => (int) $p->sort_order,
            'is_active'  => (bool) $p->is_active,
        ];
    }
}