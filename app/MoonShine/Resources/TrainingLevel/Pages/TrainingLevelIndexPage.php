<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\TrainingLevel\Pages;

use App\MoonShine\Resources\TrainingLevel\TrainingLevelResource;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;

/**
 * @extends IndexPage<TrainingLevelResource>
 */
final class TrainingLevelIndexPage extends IndexPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'title')->unescape()->updateOnPreview(),
            Text::make('Ярлык', 'label')->unescape()->updateOnPreview(),
            Text::make('Уровень прописью', 'level_text')->unescape()->updateOnPreview(),
            Number::make('Цифра', 'number')->updateOnPreview(),
            Text::make('Альтернатива', 'alternative')->unescape()->unescape()->updateOnPreview(),
            Text::make('Сортировка', 'sorting')->updateOnPreview(),
        ];
    }
}
