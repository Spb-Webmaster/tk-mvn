<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Response;
use Illuminate\Database\Seeder;

/**
 * Благодарственные письма в блоке на главной: какие показывать и в каком порядке.
 *
 * Записи ищутся по фрагменту заголовка, а не по id — на проде идентификаторы
 * могут отличаться от локальных.
 */
class ResponseHomeLettersSeeder extends Seeder
{
    /** Фрагмент заголовка => порядок вывода */
    private const LETTERS = [
        'Газпром Нефть'  => 1,
        'Мегаторг'       => 2,
        'Невский Альянс' => 3,
    ];

    public function run(): void
    {
        Response::where('on_home', 1)->update(['on_home' => 0]);

        foreach (self::LETTERS as $titlePart => $sorting) {
            $letter = Response::where('title', 'like', '%' . $titlePart . '%')
                ->whereNotNull('img')
                ->where('img', '!=', '')
                ->first();

            if (!$letter) {
                $this->command?->warn("Письмо «{$titlePart}» не найдено — пропущено.");
                continue;
            }

            $letter->update([
                'on_home' => 1,
                'sorting' => $sorting,
            ]);

            $this->command?->info("{$sorting}. {$letter->title}");
        }
    }
}
