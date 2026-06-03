<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Photo\Pages;

use App\Models\Photo;
use App\Models\PhotoCategory;
use App\MoonShine\Fields\InlineSelectField;
use App\MoonShine\Resources\Photo\PhotoResource;
use Illuminate\Support\HtmlString;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Preview;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

/**
 * @extends IndexPage<PhotoResource>
 */
final class PhotoIndexPage extends IndexPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            Image::make('Картинка', 'img'),
            Text::make('Заголовок', 'title')->unescape()->updateOnPreview(),
            InlineSelectField::make('Категории', 'categories')
                ->options(fn() => PhotoCategory::orderBy('title')->pluck('title', 'id'))
                ->saveUrl(fn(Photo $item) => route('photo.categories.update', $item->id)),
            Preview::make('Галерея', 'gallery')->changePreview(function ($value, $field) {
                $item    = $field->getData()?->getOriginal();
                $gallery = $item?->gallery ?? [];
                $count   = is_array($gallery) ? count($gallery) : 0;
                return $count > 0
                    ? new HtmlString('<span style="background:#d1fae5;color:#065f46;padding:2px 8px;border-radius:4px;font-size:12px;white-space:nowrap">✓ ' . $count . ' фото</span>')
                    : new HtmlString('<span style="background:#f3f4f6;color:#9ca3af;padding:2px 8px;border-radius:4px;font-size:12px">—</span>');
            }),
            Date::make('Дата', 'created_at')->format('d.m.Y')->sortable(),
            Switcher::make('Опубликовано', 'published')->updateOnPreview(),
        ];
    }
}
