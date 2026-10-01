<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EventTheme;

class EventThemeController extends Controller
{
    public function __invoke()
    {
        $now = now();

        $event = EventTheme::query()
            ->where('is_active', true)
            ->where('starts_at', '<=', $now)
            ->where('ends_at', '>=', $now)
            ->first();

        if (! $event) {
            return response()->json([
                'active' => false,
                'event' => null,
            ]);
        }

        return response()->json([
            'active' => true,

            'event' => [
                'id' => $event->id,
                'name' => $event->name,
                'slug' => $event->slug,
                'theme' => $event->theme,
                'starts_at' => $event->starts_at->format('Y-m-d\TH:i'),
                'ends_at' => $event->ends_at->format('Y-m-d\TH:i'),
                'settings' => $event->settings ?? [],
            ],
        ]);
    }
}