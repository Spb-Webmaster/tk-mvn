<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\News\Pages;

use App\MoonShine\Resources\News\NewsResource;
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
 * @extends IndexPage<NewsResource>
 */
final class NewsIndexPage extends IndexPage
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
            Date::make('Дата', 'created_at')->format('d.m.Y')->sortable(),
            Preview::make('Галерея', 'gallery')->changePreview(function ($value, $field) {
                $item    = $field->getData()?->getOriginal();
                $gallery = $item?->gallery ?? [];
                $count   = is_array($gallery) ? count($gallery) : 0;
                return $count > 0
                    ? new HtmlString('<span style="background:#d1fae5;color:#065f46;padding:2px 8px;border-radius:4px;font-size:12px;white-space:nowrap">✓ ' . $count . ' фото</span>')
                    : new HtmlString('<span style="background:#f3f4f6;color:#9ca3af;padding:2px 8px;border-radius:4px;font-size:12px">—</span>');
            }),
            Switcher::make('Опубликовано', 'published')->updateOnPreview(),
        ];
    }
}
