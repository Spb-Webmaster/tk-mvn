<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Photo;

use App\Models\Photo;
use App\MoonShine\Resources\Photo\Pages\PhotoFormPage;
use App\MoonShine\Resources\Photo\Pages\PhotoIndexPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\MenuManager\Attributes\Group;
use MoonShine\MenuManager\Attributes\Order;
use MoonShine\Support\Attributes\Icon;
use MoonShine\Support\Enums\SortDirection;

/**
 * @extends ModelResource<Photo, PhotoIndexPage, PhotoFormPage>
 */
#[Icon('photo')]
#[Group('Контент', 'document-text')]
#[Order(21)]
class PhotoResource extends ModelResource
{
    protected string $model = Photo::class;

    protected string $column = 'title';
    protected string $sortColumn = 'created_at';
    protected SortDirection $sortDirection = SortDirection::DESC;
    protected bool $simplePaginate = true;

    public function getTitle(): string
    {
        return 'Фотографии';
    }

    protected function pages(): array
    {
        return [
            PhotoIndexPage::class,
            PhotoFormPage::class,
        ];
    }

    protected function search(): array
    {
        return ['id', 'title', 'slug'];
    }
}
