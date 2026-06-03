<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\TrainingCategory\Pages;

use App\Enums\Resources\TeaserTemplate;
use App\Models\TrainingCategory;
use App\MoonShine\Resources\TrainingCategory\TrainingCategoryResource;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Support\ListOf;
use MoonShine\TinyMce\Fields\TinyMce;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use Throwable;

/**
 * @extends FormPage<TrainingCategoryResource, TrainingCategory>
 */
final class TrainingCategoryFormPage extends FormPage
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
                Select::make('Шаблон вывода программ', 'teaser_template')
                    ->options(TeaserTemplate::toOptions())
                    ->default(TeaserTemplate::Training->value),
            ]),

            Box::make('Изображение', [
                Image::make('Изображение', 'image')
                    ->disk('public')
                    ->dir('training-categories'),
            ]),

            Box::make('Описание', [
                Textarea::make('Краткое описание', 'short_description'),
                TinyMce::make('Полное описание', 'description'),
            ]),
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    protected function formButtons(): ListOf
    {
        return parent::formButtons();
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [];
    }

    protected function modifyFormComponent(FormBuilderContract $component): FormBuilderContract
    {
        return $component;
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [...parent::topLayer()];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [...parent::mainLayer()];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [...parent::bottomLayer()];
    }
}
