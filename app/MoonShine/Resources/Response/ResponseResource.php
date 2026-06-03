<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Response;

use App\Models\Response;
use App\MoonShine\Resources\Response\Pages\ResponseFormPage;
use App\MoonShine\Resources\Response\Pages\ResponseIndexPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\MenuManager\Attributes\Group;
use MoonShine\MenuManager\Attributes\Order;
use MoonShine\Support\Attributes\Icon;
use MoonShine\Support\Enums\SortDirection;

/**
 * @extends ModelResource<Response, ResponseIndexPage, ResponseFormPage>
 */
#[Icon('chat-bubble-left-right')]
#[Group('Контент', 'document-text')]
#[Order(25)]
class ResponseResource extends ModelResource
{
    protected string $model = Response::class;

    protected string $column = 'title';
    protected string $sortColumn = 'created_at';
    protected SortDirection $sortDirection = SortDirection::DESC;
    protected bool $simplePaginate = true;

    public function getTitle(): string
    {
        return 'Отзывы';
    }

    protected function pages(): array
    {
        return [
            ResponseIndexPage::class,
            ResponseFormPage::class,
        ];
    }

    protected function search(): array
    {
        return ['id', 'title', 'slug'];
    }
}
