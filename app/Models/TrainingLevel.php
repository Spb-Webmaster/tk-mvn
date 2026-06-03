<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingLevel extends Model
{
    protected $fillable = [
        'title',
        'label',
        'level_text',
        'number',
        'alternative',
        'sorting',
    ];

    protected function casts(): array
    {
        return [
            'number'  => 'integer',
            'sorting' => 'integer',
        ];
    }

    public function trainings(): HasMany
    {
        return $this->hasMany(Training::class);
    }
}
