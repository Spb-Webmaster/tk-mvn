<?php

namespace App\Models;

use App\Enums\Resources\FullTemplate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Response extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'template',
        'subtitle',
        'short_desc',
        'img',
        'desc',
        'img2',
        'desc2',
        'html',
        'html2',
        'published',
        'params',
        'video',
        'gallery',
        'files',
        'metatitle',
        'description',
        'keywords',
        'script',
        'sorting',
        'faq',
        'custom_field',
        'custom_field2',
        'custom_field3',
    ];
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(ResponseCategory::class, 'response_response_category');
    }
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', 1)->orderByDesc('created_at');
    }

    protected function casts(): array
    {
        return [
            'published' => 'integer',
            'sorting'   => 'integer',
            'template'  => FullTemplate::class,
            'video'     => 'array',
            'gallery'   => 'array',
            'files'     => 'array',
            'faq'       => 'array',
            'params'    => 'array',
        ];
    }
}
