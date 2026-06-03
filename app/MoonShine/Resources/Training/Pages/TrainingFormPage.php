<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Training\Pages;

use App\Enums\Resources\FullTemplate;
use App\Models\Training;
use App\MoonShine\Resources\Training\TrainingResource;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use App\MoonShine\Resources\TrainingCategory\TrainingCategoryResource;
use App\MoonShine\Resources\TrainingLevel\TrainingLevelResource;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Support\ListOf;
use MoonShine\TinyMce\Fields\TinyMce;
use MoonShine\UI\Components\Collapse;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\UI\Fields\File;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Json;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\DateRange;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use App\Support\FileNaming;
use Illuminate\Http\UploadedFile;
use Throwable;

/**
 * @extends FormPage<TrainingResource, Training>
 */
final class TrainingFormPage extends FormPage
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
                                Text::make('Подзаголовок', 'subtitle')->unescape(),
                                TinyMce::make('Анонс', 'short_desc'),
                            ])->columnSpan(9),

                            Column::make([
                                Box::make([
                                    Switcher::make('Опубликовано', 'published')->default(1),
                                    Number::make('Сортировка', 'sorting')->default(1),
                                    Select::make('Шаблон', 'template')
                                        ->options(FullTemplate::toOptions())
                                        ->default(FullTemplate::Default->value)
                                        ->required(),
                                    BelongsTo::make('Уровень', 'trainingLevel', resource: TrainingLevelResource::class)
                                        ->nullable()
                                        ->searchable(),
                                    BelongsToMany::make('Категории', 'categories', resource: TrainingCategoryResource::class)
                                        ->selectMode()
                                        ->searchable(),
                                ]),
                            ])->columnSpan(3),
                        ]),
                    ])->icon('document-text'),

                    Tab::make('Медиа', [
                        Grid::make([
                            Column::make([
                                Image::make(__('Изображение'), 'img')
                                    ->disk('public')
                                    ->dir('content/images')
                                    ->allowedExtensions(['jpg', 'png', 'jpeg', 'gif', 'svg'])
                                    ->removable(),

                                Collapse::make('Видео', [
                                    Json::make('', 'video')->fields([
                                        Text::make('Название', 'title'),
                                        Image::make('Постер', 'poster')
                                            ->disk('public')
                                            ->dir('content/video/posters')
                                            ->allowedExtensions(['jpg', 'png', 'jpeg', 'gif', 'svg'])
                                            ->removable(),
                                        File::make('Файл', 'file')
                                            ->disk('public')
                                            ->dir('content/video')
                                            ->accept('video/*')
                                            ->removable(),
                                        Text::make('YouTube', 'url')->hint('Ссылка на YouTube'),
                                        Text::make('Rutube', 'rutube')->hint('Ссылка на Rutube'),
                                    ])->vertical()->creatable(limit: 6)->removable(),
                                ]),

                                Collapse::make('Галерея', [
                                    Json::make('', 'gallery')->fields([
                                        Text::make('', 'label')->hint('Заголовок изображения'),
                                        Image::make(__('Изображение'), 'image')
                                            ->disk('public')
                                            ->dir('content/gallery')
                                            ->allowedExtensions(['jpg', 'png', 'jpeg', 'gif', 'svg'])
                                            ->removable(),
                                    ])->vertical()->creatable(limit: 100)->removable(),
                                ]),

                                Collapse::make('Файлы', [
                                    Json::make('', 'files')->fields([
                                        Text::make('', 'label')->hint('Заголовок'),
                                        File::make('', 'file')
                                            ->disk('public')
                                            ->dir('content/files')
                                            ->customName(fn(UploadedFile $file) => FileNaming::deduplicate($file, 'content/files'))
                                            ->hint('Файл'),
                                    ])->vertical()->creatable(limit: 100)->removable(),
                                ]),
                            ])->columnSpan(9),

                            Column::make([])->columnSpan(3),

                            Column::make([
                                Textarea::make('HTML-блок', 'html'),
                                TinyMce::make('Описание', 'desc'),

                                Image::make(__('Изображение на всю ширину'), 'img2')
                                    ->disk('public')
                                    ->dir('content/images')
                                    ->allowedExtensions(['jpg', 'png', 'jpeg', 'gif', 'svg'])
                                    ->removable(),

                                Textarea::make('HTML-блок 2', 'html2'),
                                TinyMce::make('Описание 2', 'desc2'),
                            ])->columnSpan(12),
                        ]),
                    ])->icon('photo'),

                    Tab::make('Расписание', [
                        Grid::make([
                            Column::make([
                                Box::make('Альтернатива', [

                                    TinyMce::make('Альтернативный текст', 'custom_field'),
                                ]),
                            ])->columnSpan(9),
                        ]),
                    ])->icon('book-open'),

                    Tab::make('Программа', [
                        Grid::make([
                            Column::make([
                                Box::make('Даты и место', [
                                    DateRange::make('Даты проведения', 'ev_date')
                                        ->fromTo('ev_date_from', 'ev_date_to'),
                                    Text::make('Время проведения', 'ev_time'),
                                    Text::make('Место проведения', 'ev_location')->default('Санкт-Петербург'),
                                    Text::make('Формат', 'ev_format')->default('Online'),
                                ]),
                            ])->columnSpan(6),
                            Column::make([
                                Box::make('Стоимость', [
                                    Number::make('Цена для физических лиц', 'ev_price_individual'),
                                    Number::make('Цена для юридических лиц', 'ev_price_legal'),
                                ]),
                            ])->columnSpan(6),
                            Column::make([
                                Box::make('Цели тренинга - запишется в расписание', [
                                    Json::make('', 'ev_goals')->fields([
                                        Text::make('Название блока', 'title'),
                                        Json::make('Пункты', 'items')->fields([
                                            Textarea::make('', 'value'),
                                        ])->vertical()->creatable(limit: 30)->removable(),
                                    ])->vertical()->creatable()->removable(),
                                ]),

                            ])->columnSpan(6),
                            Column::make([
                                Box::make('Задачи тренинга', [
                                    Json::make('', 'ev_tasks')->fields([
                                        Text::make('Название блока', 'title'),
                                        Textarea::make('', 'value'),
                                    ])->vertical()->creatable()->removable(),
                                ]),
                            ])->columnSpan(6),
                            Column::make([
                                Box::make('Методы ведения', [
                                    Json::make('', 'ev_methods')->fields([
                                        Text::make('Название блока', 'title'),
                                        Json::make('Пункты', 'items')->fields([
                                            Textarea::make('', 'value'),
                                        ])->vertical()->creatable(limit: 30)->removable(),
                                    ])->vertical()->creatable()->removable(),
                                ]),
                            ])->columnSpan(6),
                            Column::make([
                                Box::make('Что получают участники', [
                                    Json::make('', 'ev_results')->fields([
                                        Text::make('Название блока', 'title'),
                                        Json::make('Пункты', 'items')->fields([
                                            Textarea::make('', 'value'),
                                        ])->vertical()->creatable(limit: 30)->removable(),
                                    ])->vertical()->creatable()->removable(),
                                ]),
                            ])->columnSpan(6),
                            Column::make([
                                Box::make('Модули тренинга', [
                                    Json::make('', 'ev_modules')->fields([
                                        Text::make('Название блока', 'title'),
                                            TinyMce::make('', 'value'),
                                    ])->vertical()->creatable()->removable(),
                                ]),

                            ])->columnSpan(12),
                        ]),
                    ])->icon('academic-cap'),

                    Tab::make('SEO', [
                        Text::make('Мета-заголовок', 'metatitle')->unescape(),
                        Text::make('Мета-описание', 'description')->unescape(),
                        Text::make('Ключевые слова', 'keywords')->unescape(),
                        Textarea::make('Скрипт', 'script')->unescape(),
                    ])->icon('magnifying-glass'),

                    Tab::make('Дополнительно', [
                        Column::make([
                            Collapse::make('Вопрос/Ответ', [
                                Json::make('', 'faq')->fields([
                                    Text::make('Заголовок', 'title'),
                                    Json::make('Опции', 'options')->fields([
                                        Textarea::make('Вопрос', 'question'),
                                        TinyMce::make('Ответ', 'answer'),
                                    ])->vertical()->creatable(limit: 50)->removable(),
                                ])->vertical()->creatable(limit: 1)->removable(),
                            ]),
                        ]),
                    ])->icon('adjustments-horizontal'),
                ]),
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
