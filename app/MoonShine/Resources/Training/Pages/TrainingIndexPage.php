<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Training\Pages;

use App\Models\Training;
use App\Models\TrainingCategory;
use App\MoonShine\Fields\InlineSelectField;
use App\MoonShine\Resources\Training\TrainingResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Fields\DateRange;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

/**
 * @extends IndexPage<TrainingResource>
 */
final class TrainingIndexPage extends IndexPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Дата', 'ev_date_from', fn($item) => $item->ev_date_from
                ? $item->ev_day_month . ' ' . $item->ev_date_from->year
                : '—'),
            Image::make('Картинка', 'img'),

            Number::make('Цена физ лиц', 'ev_price_individual')->updateOnPreview(),
            Number::make('Цена юр лиц', 'ev_price_legal')->updateOnPreview(),
            Text::make('Заголовок', 'title')->unescape()->updateOnPreview(),
            Text::make('Шаблон', 'template', fn($item) => $item->template?->label() ?? '—'),
            InlineSelectField::make('Категории', 'categories')
                ->options(fn() => TrainingCategory::orderBy('title')->pluck('title', 'id'))
                ->saveUrl(fn(Training $item) => route('training.categories.update', $item->id)),
            Switcher::make('Опубликовано', 'published')->updateOnPreview(),
            Text::make('Сортировка', 'sorting')->updateOnPreview(),
        ];
    }
}
