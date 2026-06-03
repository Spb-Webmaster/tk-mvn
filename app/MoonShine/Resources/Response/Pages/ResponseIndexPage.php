<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Response\Pages;

use App\Models\Response;
use App\Models\ResponseCategory;
use App\MoonShine\Fields\InlineSelectField;
use App\MoonShine\Resources\Response\ResponseResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

/**
 * @extends IndexPage<ResponseResource>
 */
final class ResponseIndexPage extends IndexPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            Image::make('Фото', 'img'),
            Text::make('Заголовок', 'title')->unescape()->updateOnPreview(),
            InlineSelectField::make('Категории', 'categories')
                ->options(fn() => ResponseCategory::orderBy('title')->pluck('title', 'id'))
                ->saveUrl(fn(Response $item) => route('response.categories.update', $item->id)),
            Date::make('Дата', 'created_at')->format('d.m.Y')->sortable(),
            Switcher::make('Опубликовано', 'published')->updateOnPreview(),
        ];
    }
}
