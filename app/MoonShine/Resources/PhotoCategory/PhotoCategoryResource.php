<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\PhotoCategory;

use App\Models\PhotoCategory;
use App\MoonShine\Resources\PhotoCategory\Pages\PhotoCategoryFormPage;
use App\MoonShine\Resources\PhotoCategory\Pages\PhotoCategoryIndexPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\MenuManager\Attributes\Group;
use MoonShine\MenuManager\Attributes\Order;
use MoonShine\Support\Attributes\Icon;
use MoonShine\Support\Enums\SortDirection;

/**
 * @extends ModelResource<PhotoCategory, PhotoCategoryIndexPage, PhotoCategoryFormPage>
 */
#[Icon('tag')]
#[Group('Контент', 'document-text')]
#[Order(20)]
class PhotoCategoryResource extends ModelResource
{
    protected string $model = PhotoCategory::class;

    protected string $column = 'title';
    protected string $sortColumn = 'sorting';
    protected SortDirection $sortDirection = SortDirection::ASC;

    public function getTitle(): string
    {
        return 'Категории фотографий';
    }

    protected function pages(): array
    {
        return [
            PhotoCategoryIndexPage::class,
            PhotoCategoryFormPage::class,
        ];
    }

    protected function search(): array
    {
        return ['id', 'title'];
    }
}
