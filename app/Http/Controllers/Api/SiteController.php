<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\SocialLink;
use App\Models\EventTheme;

class SiteController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'settings' => Settings::pluck('value', 'key'),
            'event_theme' => EventTheme::current()?->theme,
            'socials'  => SocialLink::where('is_active', true)
                ->orderBy('sort_order')
                ->get(['platform', 'label', 'handle', 'url']),
        ]);
    }

}