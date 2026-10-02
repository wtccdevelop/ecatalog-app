<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventTheme;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EventThemeController extends Controller
{
    public function index()
    {
        return response()->json(
            EventTheme::orderByDesc('starts_at')->get()->map(fn ($e) => $this->map($e))->values()
        );
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        return response()->json($this->map(EventTheme::create($data)), 201);
    }

    public function update(Request $request, EventTheme $eventTheme)
    {
        $eventTheme->update($this->validated($request, $eventTheme));

        return response()->json($this->map($eventTheme->refresh()));
    }

    public function destroy(EventTheme $eventTheme)
    {
        $eventTheme->delete();

        return response()->json(['ok' => true]);
    }

    public function toggle(EventTheme $eventTheme)
    {
        $turnOn = ! $eventTheme->is_active;

        if ($turnOn) {
            $this->assertNoConflict($eventTheme->starts_at, $eventTheme->ends_at, $eventTheme->id);
        }

        $eventTheme->update(['is_active' => $turnOn]);

        return response()->json($this->map($eventTheme->refresh()));
    }

    private function validated(Request $request, ?EventTheme $current = null): array
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'theme'     => ['required', 'in:'.implode(',', EventTheme::THEMES)],
            'starts_at' => ['required', 'date'],
            'ends_at'   => ['required', 'date', 'after:starts_at'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required'      => 'Nama event wajib diisi.',
            'theme.required'     => 'Pilih tema.',
            'theme.in'           => 'Tema tidak valid.',
            'starts_at.required' => 'Waktu mulai wajib diisi.',
            'ends_at.required'   => 'Waktu selesai wajib diisi.',
            'ends_at.after'      => 'Waktu selesai harus setelah waktu mulai.',
        ]);

        $start    = Carbon::parse($request->input('starts_at'));
        $end      = Carbon::parse($request->input('ends_at'));
        $isActive = $request->boolean('is_active');

        if ($isActive) {
            $this->assertNoConflict($start, $end, $current?->id);
        }

        return [
            'name'      => $request->input('name'),
            'theme'     => $request->input('theme'),
            'starts_at' => $start,
            'ends_at'   => $end,
            'is_active' => $isActive,
        ];
    }

    private function assertNoConflict($start, $end, ?int $ignoreId): void
    {
        $c = EventTheme::conflicting($start, $end, $ignoreId)->first();

        if ($c) {
            throw ValidationException::withMessages([
                'starts_at' => sprintf(
                    'Jadwal bentrok dengan event "%s" (%s – %s). Ubah tanggal atau nonaktifkan event tersebut.',
                    $c->name,
                    $c->starts_at->format('d M Y H:i'),
                    $c->ends_at->format('d M Y H:i'),
                ),
            ]);
        }
    }

    private function map(EventTheme $e): array
    {
        $now = now();

        $state = match (true) {
            ! $e->is_active        => 'inactive',
            $e->starts_at->gt($now) => 'scheduled',
            $e->ends_at->lte($now)  => 'expired',
            default                 => 'live',
        };

        return [
            'id'        => $e->id,
            'name'      => $e->name,
            'theme'     => $e->theme,
            'starts_at' => $e->starts_at->format('Y-m-d\TH:i'),
            'ends_at'   => $e->ends_at->format('Y-m-d\TH:i'),
            'is_active' => (bool) $e->is_active,
            'state'     => $state,
        ];
    }
}