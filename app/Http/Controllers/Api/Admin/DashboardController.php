<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Stores;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $today = today()->toDateString();

        return response()->json([
            'counts' => [
                'brands'   => Brand::count(),
                'products' => Product::count(),
                'banners'  => Banner::count(),
                'stores'   => Stores::count(),
            ],
            'visitors_today'    => DB::table('visitor_days')->where('visit_date', $today)->count(),
            'page_views_today'  => DB::table('page_views')->where('visit_date', $today)->where('event', 'pageview')->count(),
        ]);
    }
}