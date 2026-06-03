<?php

namespace App\Models;

use App\Enums\Resources\TeaserTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ResponseCategory extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'image',
        'short_description',
        'description',
        'sorting',
        'teaser_template',
    ];

    protected function casts(): array
    {
        return [
            'sorting'         => 'integer',
            'teaser_template' => TeaserTemplate::class,
        ];
    }

    public function responses(): BelongsToMany
    {
        return $this->belongsToMany(Response::class, 'response_response_category');
    }
}
