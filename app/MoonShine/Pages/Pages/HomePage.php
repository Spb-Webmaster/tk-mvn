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
use MoonShine\UI\Components\Collapse;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Divider;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Json;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

class HomePage extends Page
{
    public function getTitle(): string
    {
        return 'Главная';
    }

    private function getSetting(): Setting
    {
        return Setting::getGroup('home');
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
                                    ->hint('Например: Санкт-Петербург · с 2010 года'),
                                Text::make('Заголовок', 'hero_title')
                                    ->required(),
                                Text::make('Подзаголовок', 'hero_subtitle')
                                    ->hint('Например: Тренинги · Консалтинг · Коучинг'),
                                Textarea::make('Описание', 'hero_desc')->unescape(),
                            ]),
                        ])->icon('home'),

                        Tab::make('Программы', [
                            Box::make('Заголовок раздела', [
                                Text::make('Метка', 'programs_eyebrow')
                                    ->hint('Например: Форматы обучения'),
                                Text::make('Заголовок', 'programs_title')
                                    ->hint('Например: Что мы предлагаем'),
                                Textarea::make('Краткое описание', 'programs_lead')->unescape(),
                            ]),

                        ])->icon('academic-cap'),

                        Tab::make('О тренере', [
                            Box::make('Блок «О тренере»', [
                                Text::make('Заголовок', 'about_title')
                                    ->hint('Можно использовать HTML: Василий<br>Никольский'),
                                TinyMce::make('Описание', 'about_body'),
                            ]),
                        ])->icon('user'),

                        Tab::make('Основное', [
                            ID::make('ID'),
                            Grid::make([
                                Column::make([
                                    Box::make([
                                        Text::make('Заголовок', 'title')->required()->unescape(),
                                    ]),
                                ])->columnSpan(9),

                                Column::make([
                                    Box::make([
                                        Divider::make('Главная должна быть опубликована'),

                                        Switcher::make('Опубликовано', 'published')->default(1),

                                    ]),
                                ])->columnSpan(3),
                            ]),
                        ])->icon('document-text'),

                        Tab::make('Медиа', [
                            Grid::make([

                                Column::make([
                                    Divider::make('НЕ пишите заголовок h1 в описании'),

                                    TinyMce::make('Описание', 'desc'),
                                ])->columnSpan(12),
                            ]),
                        ])->icon('photo'),

                        Tab::make('SEO', [
                            Text::make('Мета-заголовок', 'metatitle')->unescape(),
                            Text::make('Мета-описание', 'description')->unescape(),
                            Text::make('Ключевые слова', 'keywords')->unescape(),
                            Textarea::make('Скрипт', 'script')->unescape(),
                        ])->icon('magnifying-glass'),

                        Tab::make('Дополнительно', [
                            Column::make([
                                Collapse::make('Вопрос/Ответ', [
                                    Json::make('', 'faq')->fields([
                                        Text::make('Заголовок', 'title'),
                                        Json::make('Опции', 'options')->fields([
                                            Textarea::make('Вопрос', 'question')->unescape(),
                                            TinyMce::make('Ответ', 'answer'),
                                        ])->vertical()->creatable(limit: 50)->removable(),
                                    ])->vertical()->creatable(limit: 1)->removable(),
                                ]),
                            ]),
                        ])->icon('adjustments-horizontal'),
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
