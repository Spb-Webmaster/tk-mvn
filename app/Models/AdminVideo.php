<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AdminVideo extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'sorting',
        'video',
        'desc',
    ];

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sorting')->orderByDesc('id');
    }

    protected function casts(): array
    {
        return [
            'sorting' => 'integer',
        ];
    }
}
