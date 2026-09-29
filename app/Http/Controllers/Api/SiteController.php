<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\SocialLink;

class SiteController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'settings' => Settings::pluck('value', 'key'),
            'socials'  => SocialLink::where('is_active', true)
                ->orderBy('sort_order')
                ->get(['platform', 'label', 'handle', 'url']),
        ]);
    }
}