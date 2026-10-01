<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceCategories extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_active'  => 'boolean',
            'min_price'  => 'integer',
            'max_price'  => 'integer',
        ];
    }
}