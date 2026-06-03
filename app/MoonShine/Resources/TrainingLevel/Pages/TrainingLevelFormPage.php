<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\TrainingLevel\Pages;

use App\MoonShine\Resources\TrainingLevel\TrainingLevelResource;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;

/**
 * @extends FormPage<TrainingLevelResource>
 */
final class TrainingLevelFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make('ID'),
                Text::make('Название', 'title')->required()->unescape(),
                Text::make('Ярлык (тег уровня)', 'label')->unescape()->hint('Например: Переговоры в бизнесе · Базовый'),
                Text::make('Уровень прописью', 'level_text')->unescape(),
                Number::make('Цифра', 'number'),
                Text::make('Альтернатива', 'alternative')->unescape(),
                Number::make('Сортировка', 'sorting')->default(0),
            ]),
        ];
    }
}
