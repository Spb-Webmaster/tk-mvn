<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\MailLog;

use App\Models\MailLog;
use App\MoonShine\Resources\MailLog\Pages\MailLogDetailPage;
use App\MoonShine\Resources\MailLog\Pages\MailLogIndexPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\Attributes\Icon;
use MoonShine\Support\Enums\Action;
use MoonShine\Support\Enums\SortDirection;
use MoonShine\Support\ListOf;

/**
 * @extends ModelResource<MailLog, MailLogIndexPage, MailLogDetailPage>
 */
#[Icon('envelope')]
class MailLogResource extends ModelResource
{
    protected string $model = MailLog::class;

    protected string $column = 'subject';
    protected string $sortColumn = 'created_at';
    protected SortDirection $sortDirection = SortDirection::DESC;

    public function getTitle(): string
    {
        return 'Исходящие письма';
    }

    protected function pages(): array
    {
        return [
            MailLogIndexPage::class,
            MailLogDetailPage::class,
        ];
    }

    protected function activeActions(): ListOf
    {
        return parent::activeActions()->except(Action::CREATE, Action::UPDATE);
    }

    protected function search(): array
    {
        return ['subject', 'form'];
    }
}
