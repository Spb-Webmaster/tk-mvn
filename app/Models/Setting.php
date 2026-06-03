<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Setting extends Model
{
    protected $fillable = ['group', 'data'];

    protected $casts = [
        'data' => 'array',
    ];

    // Хранит уже загруженные группы в памяти на время одного запроса.
    // Благодаря этому повторный вызов getGroup('constants') не идёт в БД —
    // возвращается уже готовый объект из этого массива.
    private static array $cache = [];

    public static function getGroup(string $group): self
    {
        // Оператор ??= : если ключ ещё не существует — выполняет firstOrCreate
        // и записывает результат. При следующем вызове с тем же $group
        // просто вернёт закешированный объект.
        return static::$cache[$group] ??= static::firstOrCreate(['group' => $group], ['data' => []]);
    }

    public function getValue(string $key, mixed $default = null): mixed
    {
        return data_get($this->data, $key, $default);
    }


}
