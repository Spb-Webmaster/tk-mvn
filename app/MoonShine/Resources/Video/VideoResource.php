<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Video;

use App\Models\Video;
use App\MoonShine\Resources\Video\Pages\VideoFormPage;
use App\MoonShine\Resources\Video\Pages\VideoIndexPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\MenuManager\Attributes\Group;
use MoonShine\MenuManager\Attributes\Order;
use MoonShine\Support\Attributes\Icon;
use MoonShine\Support\Enums\SortDirection;

/**
 * @extends ModelResource<Video, VideoIndexPage, VideoFormPage>
 */
#[Icon('film')]
#[Group('Контент', 'document-text')]
#[Order(23)]
class VideoResource extends ModelResource
{
    protected string $model = Video::class;

    protected string $column = 'title';
    protected string $sortColumn = 'created_at';
    protected SortDirection $sortDirection = SortDirection::DESC;
    protected bool $simplePaginate = true;

    public function getTitle(): string
    {
        return 'Видео';
    }

    protected function pages(): array
    {
        return [
            VideoIndexPage::class,
            VideoFormPage::class,
        ];
    }

    protected function search(): array
    {
        return ['id', 'title', 'slug'];
    }
}
