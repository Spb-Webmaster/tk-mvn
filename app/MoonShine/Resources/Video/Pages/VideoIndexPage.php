<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Video\Pages;

use App\MoonShine\Resources\Video\VideoResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use Illuminate\Support\Facades\Storage;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Position;
use MoonShine\UI\Fields\Preview;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

/**
 * @extends IndexPage<VideoResource>
 */
final class VideoIndexPage extends IndexPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Position::make(),
            Preview::make('Постер', 'video', fn($item) => is_array($item->video) && isset($item->video[0]['poster'])
                ? Storage::url($item->video[0]['poster'])
                : null
            )->image(),
            Text::make('Заголовок', 'title')->unescape()->updateOnPreview(),
            Date::make('Дата', 'created_at')->format('d.m.Y')->sortable(),
            Switcher::make('Опубликовано', 'published')->updateOnPreview(),
        ];
    }
}
