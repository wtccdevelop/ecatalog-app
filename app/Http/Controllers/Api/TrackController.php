<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\Tracker;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            Tracker::record($request);
        } catch (\Throwable $e) {
            report($e); // tracking tidak boleh mengganggu pengunjung
        }

        return response()->noContent();
    }
}