<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\ResponseCategory\Pages;

use App\MoonShine\Resources\ResponseCategory\ResponseCategoryResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;

/**
 * @extends IndexPage<ResponseCategoryResource>
 */
final class ResponseCategoryIndexPage extends IndexPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Название', 'title')->unescape()->updateOnPreview(),
            Text::make('Шаблон', 'teaser_template', fn($item) => $item->teaser_template?->label() ?? '—'),
            Text::make('Сортировка', 'sorting')->updateOnPreview(),
        ];
    }
}
