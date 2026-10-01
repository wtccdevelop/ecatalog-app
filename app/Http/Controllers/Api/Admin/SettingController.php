<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    // hanya key ini yang boleh diubah dari admin
    private const KEYS = ['wa_number', 'operating_hours', 'about_1', 'about_2'];

    public function index()
    {
        return response()->json($this->all());
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'wa_number'       => ['required', 'regex:/^62\d{8,13}$/'],
            'operating_hours' => ['nullable', 'string', 'max:255'],
            'about_1'         => ['nullable', 'string', 'max:2000'],
            'about_2'         => ['nullable', 'string', 'max:2000'],
        ], [
            'wa_number.required' => 'Nomor WhatsApp wajib diisi.',
            'wa_number.regex'    => 'Gunakan format 62xxxxxxxxxx (tanpa +, spasi, atau 0 di depan).',
        ]);

        foreach (self::KEYS as $key) {
            Settings::updateOrCreate(['key' => $key], ['value' => $data[$key] ?? null]);
        }

        return response()->json($this->all());
    }

    private function all(): array
    {
        $saved = Settings::whereIn('key', self::KEYS)->pluck('value', 'key');

        return collect(self::KEYS)
            ->mapWithKeys(fn ($k) => [$k => $saved[$k] ?? ''])
            ->all();
    }
}