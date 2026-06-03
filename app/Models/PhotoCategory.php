<?php

namespace App\Models;

use App\Enums\Resources\TeaserTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PhotoCategory extends Model
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

    public function photos(): BelongsToMany
    {
        return $this->belongsToMany(Photo::class, 'photo_photo_category');
    }
}
