<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\TrainingCategory;

use App\Models\TrainingCategory;
use App\MoonShine\Resources\TrainingCategory\Pages\TrainingCategoryIndexPage;
use App\MoonShine\Resources\TrainingCategory\Pages\TrainingCategoryFormPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\MenuManager\Attributes\Group;
use MoonShine\MenuManager\Attributes\Order;
use MoonShine\Support\Attributes\Icon;
use MoonShine\Support\Enums\SortDirection;

/**
 * @extends ModelResource<TrainingCategory, TrainingCategoryIndexPage, TrainingCategoryFormPage>
 */
#[Icon('tag')]
#[Group('Контент', 'document-text')]
#[Order(13)]
class TrainingCategoryResource extends ModelResource
{
    protected string $model = TrainingCategory::class;

    protected string $column = 'title';
    protected string $sortColumn = 'sorting';
    protected SortDirection $sortDirection = SortDirection::ASC;

    public function getTitle(): string
    {
        return 'Категории обучения';
    }

    protected function pages(): array
    {
        return [
            TrainingCategoryIndexPage::class,
            TrainingCategoryFormPage::class,
        ];
    }

    protected function search(): array
    {
        return ['id', 'title'];
    }
}
