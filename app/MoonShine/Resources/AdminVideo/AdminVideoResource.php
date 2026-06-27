<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\AdminVideo;

use App\Models\AdminVideo;
use App\MoonShine\Resources\AdminVideo\Pages\AdminVideoFormPage;
use App\MoonShine\Resources\AdminVideo\Pages\AdminVideoIndexPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\MenuManager\Attributes\Group;
use MoonShine\MenuManager\Attributes\Order;
use MoonShine\Support\Attributes\Icon;
use MoonShine\Support\Enums\SortDirection;

/**
 * @extends ModelResource<AdminVideo, AdminVideoIndexPage, AdminVideoFormPage>
 */
#[Icon('film')]
#[Group('Контент', 'document-text')]
#[Order(24)]
class AdminVideoResource extends ModelResource
{
    protected string $model = AdminVideo::class;

    protected string $column = 'title';
    protected string $sortColumn = 'sorting';
    protected SortDirection $sortDirection = SortDirection::ASC;
    protected bool $simplePaginate = true;

    public function getTitle(): string
    {
        return 'Видео для администратора';
    }

    protected function pages(): array
    {
        return [
            AdminVideoIndexPage::class,
            AdminVideoFormPage::class,
        ];
    }

    protected function search(): array
    {
        return ['id', 'title', 'slug'];
    }
}
