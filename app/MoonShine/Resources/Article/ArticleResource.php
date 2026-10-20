<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Article;

use App\Models\Article;
use App\MoonShine\Resources\Article\Pages\ArticleFormPage;
use App\MoonShine\Resources\Article\Pages\ArticleIndexPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\MenuManager\Attributes\Group;
use MoonShine\MenuManager\Attributes\Order;
use MoonShine\Support\Attributes\Icon;
use MoonShine\Support\Enums\SortDirection;

/**
 * @extends ModelResource<Article, ArticleIndexPage, ArticleFormPage>
 */
#[Icon('document-text')]
#[Group('Контент', 'document-text')]
#[Order(12)]
class ArticleResource extends ModelResource
{
    protected string $model = Article::class;

    protected string $column = 'title';
    protected string $sortColumn = 'created_at';
    protected SortDirection $sortDirection = SortDirection::DESC;
    protected bool $simplePaginate = true;

    public function getTitle(): string
    {
        return 'Статьи';
    }

    protected function pages(): array
    {
        return [
            ArticleIndexPage::class,
            ArticleFormPage::class,
        ];
    }

    protected function search(): array
    {
        return [
            'id',
            'title',
            'slug',
        ];
    }
}
