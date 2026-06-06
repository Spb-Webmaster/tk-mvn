<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Photo\Pages;

use App\Enums\Resources\FullTemplate;
use App\MoonShine\Resources\Photo\PhotoResource;
use App\MoonShine\Resources\PhotoCategory\PhotoCategoryResource;
use App\Support\FileNaming;
use Illuminate\Http\UploadedFile;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\TinyMce\Fields\TinyMce;
use MoonShine\UI\Components\Collapse;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Divider;
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
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends FormPage<PhotoResource>
 */
final class PhotoFormPage extends FormPage
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
                                    Divider::make('Статус публикации'),
                                    Switcher::make('Опубликовано', 'published')->default(1),
                                    Divider::make('Сортировка'),
                                    Number::make('Сортировка', 'sorting')->default(0),
                                    Divider::make('Шаблон вывода'),
                                    Select::make('Шаблон', 'template')
                                        ->options(FullTemplate::toOptions())
                                        ->default(FullTemplate::Default->value)
                                        ->required(),
                                    Divider::make('Категории'),
                                    BelongsToMany::make('Категории', 'categories', resource: PhotoCategoryResource::class)
                                        ->selectMode()
                                        ->searchable(),
                                ]),
                            ])->columnSpan(3),
                        ]),
                    ])->icon('document-text'),

                    Tab::make('Медиа', [
                        Grid::make([
                            Column::make([
                                Image::make('Изображение', 'img')
                                    ->disk('public')
                                    ->dir('content/photos/images')
                                    ->allowedExtensions(['jpg', 'png', 'jpeg', 'gif', 'svg', 'webp'])
                                    ->removable(),

                                Collapse::make('Галерея', [
                                    Image::make('', 'gallery')
                                        ->disk('public')
                                        ->dir('content/photos/gallery')
                                        ->allowedExtensions(['jpg', 'png', 'jpeg', 'gif', 'svg', 'webp'])
                                        ->multiple()
                                        ->removable(),
                                ]),

                                Collapse::make('Видео', [
                                    Json::make('', 'video')->fields([
                                        Text::make('Название', 'title'),
                                        Image::make('Постер', 'poster')
                                            ->disk('public')
                                            ->dir('content/photos/video/posters')
                                            ->allowedExtensions(['jpg', 'png', 'jpeg', 'gif', 'svg'])
                                            ->removable(),
                                        File::make('Файл', 'file')
                                            ->disk('public')
                                            ->dir('content/photos/video')
                                            ->accept('video/*')
                                            ->removable(),
                                        Text::make('YouTube', 'url')->hint('Ссылка на YouTube'),
                                        Text::make('Rutube', 'rutube')->hint('Ссылка на Rutube'),
                                    ])->vertical()->creatable(limit: 6)->removable(),
                                ]),

                                Collapse::make('Файлы', [
                                    Json::make('', 'files')->fields([
                                        Text::make('', 'label')->hint('Заголовок'),
                                        File::make('', 'file')
                                            ->disk('public')
                                            ->dir('content/photos/files')
                                            ->customName(fn(UploadedFile $file) => FileNaming::deduplicate($file, 'content/photos/files'))
                                            ->hint('Файл'),
                                    ])->vertical()->creatable(limit: 100)->removable(),
                                ]),
                            ])->columnSpan(9),

                            Column::make([])->columnSpan(3),

                            Column::make([
                                TinyMce::make('Описание', 'desc'),

                                Image::make('Изображение на всю ширину', 'img2')
                                    ->disk('public')
                                    ->dir('content/photos/images')
                                    ->allowedExtensions(['jpg', 'png', 'jpeg', 'gif', 'svg', 'webp'])
                                    ->removable(),
                            ])->columnSpan(12),
                        ]),
                    ])->icon('photo'),

                    Tab::make('SEO', [
                        Text::make('Мета-заголовок', 'metatitle')->unescape(),
                        Text::make('Мета-описание', 'description')->unescape(),
                        Text::make('Ключевые слова', 'keywords')->unescape(),
                    ])->icon('magnifying-glass'),

                    Tab::make('Дополнительно', [
                        Column::make([
                            Collapse::make('Вопрос/Ответ', [
                                Json::make('', 'faq')->fields([
                                    Text::make('Заголовок', 'title'),
                                    Json::make('Опции', 'options')->fields([
                                        Textarea::make('Вопрос', 'question')->unescape(),
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
}
