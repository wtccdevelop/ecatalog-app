<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventTheme;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EventThemeController extends Controller
{
    private const THEMES = [
        'christmas',
        'eid',
        'independence',
        'new_year',
    ];

    public function index()
    {
        return response()->json(
            EventTheme::query()
                ->orderBy('starts_at')
                ->get()
                ->map(fn (EventTheme $event) => $this->map($event))
                ->values()
        );
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $this->ensureNoOverlap(
            $data['starts_at'],
            $data['ends_at']
        );

        $event = EventTheme::create($data);

        return response()->json($this->map($event), 201);
    }

    public function show(EventTheme $eventTheme)
    {
        return response()->json($this->map($eventTheme));
    }

    public function update(Request $request, EventTheme $eventTheme)
    {
        $data = $this->validated($request);

        $this->ensureNoOverlap(
            $data['starts_at'],
            $data['ends_at'],
            $eventTheme->id
        );

        $eventTheme->update($data);

        return response()->json(
            $this->map($eventTheme->refresh())
        );
    }

    public function destroy(EventTheme $eventTheme)
    {
        $eventTheme->delete();

        return response()->json([
            'ok' => true,
        ]);
    }

    public function toggle(EventTheme $eventTheme)
    {
        $eventTheme->update([
            'is_active' => ! $eventTheme->is_active,
        ]);

        return response()->json(
            $this->map($eventTheme->refresh())
        );
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'slug' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-z0-9-]+$/',
            ],

            'theme' => [
                'required',
                'in:' . implode(',', self::THEMES),
            ],

            'starts_at' => [
                'required',
                'date',
            ],

            'ends_at' => [
                'required',
                'date',
                'after:starts_at',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'settings' => [
                'nullable',
                'array',
            ],

            'settings.show_product_decoration' => [
                'nullable',
                'boolean',
            ],

            'settings.show_header_decoration' => [
                'nullable',
                'boolean',
            ],

            'settings.show_background_effect' => [
                'nullable',
                'boolean',
            ],
        ], [
            'name.required' => 'Nama event wajib diisi.',
            'slug.required' => 'Slug event wajib diisi.',
            'slug.regex' => 'Slug hanya boleh menggunakan huruf kecil, angka, dan tanda -.',
            'theme.required' => 'Tema wajib dipilih.',
            'starts_at.required' => 'Tanggal mulai wajib diisi.',
            'ends_at.required' => 'Tanggal selesai wajib diisi.',
            'ends_at.after' => 'Tanggal selesai harus setelah tanggal mulai.',
        ]);

        return [
            'name' => $data['name'],
            'slug' => $data['slug'],
            'theme' => $data['theme'],
            'starts_at' => $data['starts_at'],
            'ends_at' => $data['ends_at'],
            'is_active' => $request->boolean('is_active'),
            'settings' => $data['settings'] ?? [
                'show_product_decoration' => true,
                'show_header_decoration' => true,
                'show_background_effect' => false,
            ],
        ];
    }

    /**
     * Event sama sekali tidak boleh bertabrakan.
     */
    private function ensureNoOverlap(
        string $startsAt,
        string $endsAt,
        ?int $ignoreId = null
    ): void {
        $query = EventTheme::query()
            ->where(function ($q) use ($startsAt, $endsAt) {
                $q->where('starts_at', '<=', $endsAt)
                    ->where('ends_at', '>=', $startsAt);
            });

        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }

        $conflict = $query->first();

        if ($conflict) {
            throw ValidationException::withMessages([
                'starts_at' => [
                    sprintf(
                        'Jadwal bertabrakan dengan event "%s" (%s - %s). Silakan pilih jadwal lain.',
                        $conflict->name,
                        $conflict->starts_at->format('d M Y H:i'),
                        $conflict->ends_at->format('d M Y H:i')
                    ),
                ],
            ]);
        }
    }

    private function map(EventTheme $event): array
    {
        $now = now();

        $state = match (true) {
            ! $event->is_active => 'inactive',

            $now->lt($event->starts_at) => 'scheduled',

            $now->gt($event->ends_at) => 'expired',

            default => 'live',
        };

        return [
            'id' => $event->id,
            'name' => $event->name,
            'slug' => $event->slug,
            'theme' => $event->theme,
            'starts_at' => $event->starts_at?->format('Y-m-d\TH:i'),
            'ends_at' => $event->ends_at?->format('Y-m-d\TH:i'),
            'is_active' => (bool) $event->is_active,
            'settings' => $event->settings ?? [],
            'state' => $state,
        ];
    }
}