<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReviews;

class ReviewController extends Controller
{
    public function toggle(ProductReviews $review)
    {
        $review->update(['is_active' => ! $review->is_active]);

        return response()->json(['is_active' => (bool) $review->is_active]);
    }

    public function destroy(ProductReviews $review)
    {
        $review->delete();

        return response()->json(['ok' => true]);
    }
}