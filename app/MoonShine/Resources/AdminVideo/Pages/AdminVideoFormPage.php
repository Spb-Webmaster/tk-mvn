<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\AdminVideo\Pages;

use App\MoonShine\Resources\AdminVideo\AdminVideoResource;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\TinyMce\Fields\TinyMce;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Divider;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;

/**
 * @extends FormPage<AdminVideoResource>
 */
final class AdminVideoFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                Tabs::make([
                    Tab::make('Основное', [
                        ID::make('ID'),
                        Grid::make([
                            Column::make([
                                Box::make([
                                    Text::make('Заголовок', 'title')->required()->unescape(),
                                    Slug::make('Slug', 'slug')->from('title')->unique()->locked(),
                                ]),
                            ])->columnSpan(9),

                            Column::make([
                                Box::make([
                                    Divider::make('Сортировка'),
                                    Number::make('Сортировка', 'sorting')->default(0),
                                ]),
                            ])->columnSpan(3),
                        ]),
                    ])->icon('document-text'),

                    Tab::make('Медиа', [
                        Text::make('Путь к файлу', 'video')
                            ->hint('Относительный путь от корня storage/app/public, например: content/admin-videos/lecture.mp4'),
                        TinyMce::make('Описание', 'desc'),
                    ])->icon('film'),
                ]),
            ]),
        ];
    }
}
