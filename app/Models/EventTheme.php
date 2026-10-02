<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class EventTheme extends Model
{
    public const THEMES = ['christmas', 'lebaran', 'kemerdekaan'];

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at'   => 'datetime',
        ];
    }

    // event yang sedang tayang sekarang
    public static function current(): ?self
    {
        $now = now();

        return static::where('is_active', true)
            ->where('starts_at', '<=', $now)
            ->where('ends_at', '>', $now)
            ->orderByDesc('starts_at')
            ->first();
    }

    // event aktif lain yang rentang waktunya beririsan
    public function scopeConflicting(Builder $q, $start, $end, ?int $ignoreId = null): Builder
    {
        return $q->where('is_active', true)
            ->where('starts_at', '<', $end)
            ->where('ends_at', '>', $start)
            ->when($ignoreId, fn ($x) => $x->where('id', '!=', $ignoreId));
    }
}