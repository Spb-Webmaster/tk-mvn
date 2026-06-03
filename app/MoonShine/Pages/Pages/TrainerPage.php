<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Pages;

use App\Models\Setting;
use Illuminate\Http\Request;
use MoonShine\Crud\JsonResponse;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Support\Attributes\AsyncMethod;
use MoonShine\Support\Enums\ToastType;
use MoonShine\TinyMce\Fields\TinyMce;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\UI\Fields\Json;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

class TrainerPage extends Page
{
    public function getTitle(): string
    {
        return 'О тренере';
    }

    private function getSetting(): Setting
    {
        return Setting::getGroup('trainer');
    }

    #[AsyncMethod]
    public function store(Request $request): JsonResponse
    {
        $setting = $this->getSetting();
        $setting->data = $request->except(['_token', '_method']);
        $setting->save();

        return JsonResponse::make()->toast('Сохранено', ToastType::SUCCESS);
    }

    private function form(): FormBuilder
    {
        return FormBuilder::make()
            ->asyncMethod('store')
            ->fill($this->getSetting()->data ?? [])
            ->fields([
                Box::make([
                    Tabs::make([

                        Tab::make('Герой', [
                            Box::make('Hero-блок', [
                                Text::make('Метка над заголовком', 'hero_eyebrow')
                                    ->hint('Например: Организационный консультант'),
                                Text::make('Заголовок', 'hero_title')
                                    ->hint('Допустим HTML: Василий<br><em>Никольский</em>')
                                    ->required(),
                                Text::make('Подзаголовок', 'hero_subtitle')
                                    ->hint('Например: Бизнес-тренер · Консультант · Коуч'),
                                Textarea::make('Цитата', 'hero_quote')
                                    ->hint('Отображается в рамке с золотой левой полосой'),
                                Textarea::make('Описание', 'hero_desc'),
                            ]),
                        ])->icon('home'),

                        Tab::make('Биография', [
                            Box::make('Биографический блок', [
                                Text::make('Метка', 'bio_eyebrow')
                                    ->hint('Например: О Василии Никольском'),
                                Text::make('Заголовок', 'bio_title'),
                                TinyMce::make('Текст', 'bio_text'),
                            ]),
                        ])->icon('user'),

                        Tab::make('Особенности', [
                            Box::make('Блок «Опыт и экспертиза»', [
                                Text::make('Метка', 'distinctions_eyebrow'),
                                Text::make('Заголовок', 'distinctions_title'),
                                Json::make('Пункты', 'distinctions_items')
                                    ->fields([
                                        Textarea::make('Текст', 'text'),
                                    ])
                                    ->vertical()
                                    ->creatable(limit: 12)
                                    ->removable(),
                            ]),
                        ])->icon('list-bullet'),

                        Tab::make('Программы', [
                            Box::make('Блок «Мероприятия»', [
                                Text::make('Метка', 'programs_eyebrow'),
                                Text::make('Заголовок', 'programs_title'),
                                Json::make('Программы', 'programs_items')
                                    ->fields([
                                        Text::make('Название', 'text'),
                                    ])
                                    ->vertical()
                                    ->creatable(limit: 20)
                                    ->removable(),
                            ]),
                        ])->icon('academic-cap'),

                        Tab::make('Сайдбар', [
                            Box::make('Специализация', [
                                Text::make('Заголовок блока', 'sidebar_spec_title'),
                                Json::make('Пункты', 'sidebar_spec_items')
                                    ->fields([
                                        Text::make('Текст', 'text'),
                                    ])
                                    ->vertical()
                                    ->creatable(limit: 15)
                                    ->removable(),
                            ]),
                            Box::make('Проводимые тренинги', [
                                Text::make('Заголовок блока', 'sidebar_trainings_title'),
                                Json::make('Пункты', 'sidebar_trainings_items')
                                    ->fields([
                                        Text::make('Текст', 'text'),
                                    ])
                                    ->vertical()
                                    ->creatable(limit: 15)
                                    ->removable(),
                            ]),
                            Box::make('Блок записи', [
                                Text::make('Заголовок', 'sidebar_contact_title'),
                                Textarea::make('Текст', 'sidebar_contact_text'),
                                Text::make('Телефон', 'sidebar_phone'),
                            ]),
                        ])->icon('rectangle-stack'),

                        Tab::make('SEO', [
                            Text::make('Мета-заголовок', 'metatitle')->unescape(),
                            Text::make('Мета-описание', 'description')->unescape(),
                            Text::make('Ключевые слова', 'keywords')->unescape(),
                        ])->icon('magnifying-glass'),

                    ]),
                ]),
            ])
            ->submit('Сохранить', ['class' => 'btn-primary']);
    }

    protected function components(): iterable
    {
        yield $this->form();
    }
}
