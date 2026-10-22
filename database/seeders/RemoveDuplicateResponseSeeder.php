<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Response;
use Illuminate\Database\Seeder;

/**
 * Удаляет дубль отзыва «Положительный заряд!» — один и тот же текст был
 * заведён дважды, с разными авторами.
 *
 * Поиск идёт по фрагменту текста, а не по id: на проде идентификаторы
 * могут отличаться. Оставляется первая запись по сортировке, остальные
 * удаляются. Повторный запуск безопасен — дублей уже не найдётся.
 */
class RemoveDuplicateResponseSeeder extends Seeder
{
    // Без кавычек и тире: в базе они лежат HTML-сущностями (&laquo; и т.п.),
    // поэтому фрагмент с типографскими символами по LIKE не находится.
    private const DUPLICATE_TEXT = 'Положительный заряд! Тренинг';

    public function run(): void
    {
        $duplicates = Response::where('desc', 'like', '%' . self::DUPLICATE_TEXT . '%')
            ->orderBy('sorting')
            ->orderBy('id')
            ->get();

        if ($duplicates->count() < 2) {
            $this->command?->info('Дублей отзыва «Положительный заряд!» не найдено — пропущено.');

            return;
        }

        $kept = $duplicates->shift();
        $this->command?->info("Оставлен: {$kept->title}");

        foreach ($duplicates as $duplicate) {
            $title = $duplicate->title;

            // Связи с категориями удалятся каскадом, detach — на случай
            // запуска на базе без внешних ключей.
            $duplicate->categories()->detach();
            $duplicate->delete();

            $this->command?->info("Удалён дубль: {$title}");
        }
    }
}
