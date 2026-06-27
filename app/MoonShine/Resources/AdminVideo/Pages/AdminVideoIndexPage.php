<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\AdminVideo\Pages;

use App\MoonShine\Resources\AdminVideo\AdminVideoResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;

/**
 * @extends IndexPage<AdminVideoResource>
 */
final class AdminVideoIndexPage extends IndexPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Заголовок', 'title')->unescape()->updateOnPreview(),
            Number::make('Сортировка', 'sorting')->updateOnPreview()->sortable(),
        ];
    }
}
