<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\TrainingLevel;

use App\Models\TrainingLevel;
use App\MoonShine\Resources\TrainingLevel\Pages\TrainingLevelFormPage;
use App\MoonShine\Resources\TrainingLevel\Pages\TrainingLevelIndexPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\MenuManager\Attributes\Group;
use MoonShine\MenuManager\Attributes\Order;
use MoonShine\Support\Attributes\Icon;
use MoonShine\Support\Enums\SortDirection;

/**
 * @extends ModelResource<TrainingLevel, TrainingLevelIndexPage, TrainingLevelFormPage>
 */
#[Icon('bars-3')]
#[Group('Контент', 'document-text')]
#[Order(14)]
class TrainingLevelResource extends ModelResource
{
    protected string $model = TrainingLevel::class;

    protected string $column = 'title';
    protected string $sortColumn = 'sorting';
    protected SortDirection $sortDirection = SortDirection::ASC;

    public function getTitle(): string
    {
        return 'Уровни обучения';
    }

    protected function pages(): array
    {
        return [
            TrainingLevelIndexPage::class,
            TrainingLevelFormPage::class,
        ];
    }

    protected function search(): array
    {
        return ['id', 'title'];
    }
}
