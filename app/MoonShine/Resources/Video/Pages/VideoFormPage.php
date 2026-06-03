<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Video\Pages;

use App\Enums\Resources\FullTemplate;
use App\MoonShine\Resources\Video\VideoResource;
use App\Support\FileNaming;
use Illuminate\Http\UploadedFile;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
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
 * @extends FormPage<VideoResource>
 */
final class VideoFormPage extends FormPage
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
                                ]),
                            ])->columnSpan(3),
                        ]),
                    ])->icon('document-text'),

                    Tab::make('Медиа', [
                        Grid::make([
                            Column::make([


                                Json::make('Видео', 'video')->fields([
                                    Text::make('Название', 'title'),
                                    Image::make('Постер', 'poster')
                                        ->disk('public')
                                        ->dir('content/videos/video/posters')
                                        ->allowedExtensions(['jpg', 'png', 'jpeg', 'gif', 'svg'])
                                        ->removable(),
                                    File::make('Файл', 'file')
                                        ->disk('public')
                                        ->dir('content/videos/video')
                                        ->accept('video/*')
                                        ->removable(),
                                    Text::make('YouTube', 'url')->hint('Ссылка на YouTube'),
                                    Text::make('Rutube', 'rutube')->hint('Ссылка на Rutube'),
                                ])->vertical()->creatable(limit: 6)->removable(),


                            ])->columnSpan(9),

                            Column::make([])->columnSpan(3),

                            Column::make([
                                TinyMce::make('Описание', 'desc'),

                            ])->columnSpan(12),
                        ]),
                    ])->icon('film'),

                    Tab::make('SEO', [
                        Text::make('Мета-заголовок', 'metatitle')->unescape(),
                        Text::make('Мета-описание', 'description')->unescape(),
                        Text::make('Ключевые слова', 'keywords')->unescape(),
                    ])->icon('magnifying-glass'),

                ]),
            ]),
        ];
    }
}
