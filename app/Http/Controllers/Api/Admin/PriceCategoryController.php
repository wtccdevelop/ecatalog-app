<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\PriceCategories;
use Illuminate\Http\Request;

class PriceCategoryController extends Controller
{
    public function index()
    {
        return response()->json(
            PriceCategories::orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(fn ($c) => $this->map($c))
                ->values()
        );
    }

    public function store(Request $request)
    {
        return response()->json(
            $this->map(PriceCategories::create($this->validated($request))),
            201
        );
    }

    public function update(Request $request, PriceCategories $priceCategory)
    {
        $priceCategory->update($this->validated($request));

        return response()->json($this->map($priceCategory->refresh()));
    }

    public function destroy(PriceCategories $priceCategory)
    {
        $priceCategory->delete();

        return response()->json(['ok' => true]);
    }

    public function toggle(PriceCategories $priceCategory)
    {
        $priceCategory->update(['is_active' => ! $priceCategory->is_active]);

        return response()->json(['is_active' => (bool) $priceCategory->is_active]);
    }

    private function validated(Request $request): array
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'min_price'  => ['nullable', 'integer', 'min:0'],
            'max_price'  => ['nullable', 'integer', 'min:0', 'gte:min_price'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_active'  => ['nullable', 'boolean'],
        ], [
            'name.required'   => 'Nama kategori wajib diisi.',
            'min_price.integer' => 'Harga minimum harus berupa angka.',
            'max_price.integer' => 'Harga maksimum harus berupa angka.',
            'max_price.gte'   => 'Harga maksimum harus lebih besar atau sama dengan harga minimum.',
        ]);

        return [
            'name'       => $request->input('name'),
            'min_price'  => $request->filled('min_price') ? (int) $request->input('min_price') : null,
            'max_price'  => $request->filled('max_price') ? (int) $request->input('max_price') : null,
            'sort_order' => (int) $request->input('sort_order', 0),
            'is_active'  => $request->boolean('is_active'),
        ];
    }

    private function map(PriceCategories $c): array
    {
        return [
            'id'         => $c->id,
            'name'       => $c->name,
            'min_price'  => $c->min_price !== null ? (int) $c->min_price : null,
            'max_price'  => $c->max_price !== null ? (int) $c->max_price : null,
            'sort_order' => (int) $c->sort_order,
            'is_active'  => (bool) $c->is_active,
        ];
    }
}