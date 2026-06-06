<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\ResponseCategory\Pages;

use App\Enums\Resources\TeaserTemplate;
use App\MoonShine\Resources\ResponseCategory\ResponseCategoryResource;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\TinyMce\Fields\TinyMce;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends FormPage<ResponseCategoryResource>
 */
final class ResponseCategoryFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make('ID'),
                Text::make('Название', 'title')->required()->unescape(),
                Slug::make('Slug', 'slug')->from('title')->unique()->locked(),
                Number::make('Сортировка', 'sorting')->default(1),
                Select::make('Шаблон вывода', 'teaser_template')
                    ->options(TeaserTemplate::toOptions())
                    ->default(TeaserTemplate::Default->value),
            ]),

            Box::make('Изображение', [
                Image::make('Изображение', 'image')
                    ->disk('public')
                    ->dir('response-categories'),
            ]),

            Box::make('Описание', [
                Textarea::make('Краткое описание', 'short_description')->unescape(),
                TinyMce::make('Полное описание', 'description'),
            ]),
        ];
    }
}
