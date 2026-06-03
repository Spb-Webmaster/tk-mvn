<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\ResponseCategory;

use App\Models\ResponseCategory;
use App\MoonShine\Resources\ResponseCategory\Pages\ResponseCategoryFormPage;
use App\MoonShine\Resources\ResponseCategory\Pages\ResponseCategoryIndexPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\MenuManager\Attributes\Group;
use MoonShine\MenuManager\Attributes\Order;
use MoonShine\Support\Attributes\Icon;
use MoonShine\Support\Enums\SortDirection;

/**
 * @extends ModelResource<ResponseCategory, ResponseCategoryIndexPage, ResponseCategoryFormPage>
 */
#[Icon('tag')]
#[Group('Контент', 'document-text')]
#[Order(24)]
class ResponseCategoryResource extends ModelResource
{
    protected string $model = ResponseCategory::class;

    protected string $column = 'title';
    protected string $sortColumn = 'sorting';
    protected SortDirection $sortDirection = SortDirection::ASC;

    public function getTitle(): string
    {
        return 'Категории отзывов';
    }

    protected function pages(): array
    {
        return [
            ResponseCategoryIndexPage::class,
            ResponseCategoryFormPage::class,
        ];
    }

    protected function search(): array
    {
        return ['id', 'title'];
    }
}
