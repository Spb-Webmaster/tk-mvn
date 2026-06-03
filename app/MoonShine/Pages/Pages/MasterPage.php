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

class MasterPage extends Page
{
    public function getTitle(): string
    {
        return 'Мастер коммуникаций';
    }

    private function getSetting(): Setting
    {
        return Setting::getGroup('master');
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

                        Tab::make('Заголовок', [
                            Box::make('Hero-блок', [
                                Text::make('Метка над заголовком', 'hero_eyebrow')
                                    ->hint('Флагманская программа'),
                                Text::make('Заголовок h1', 'hero_title')
                                    ->hint('Допустим HTML'),
                                Textarea::make('Подзаголовок', 'hero_lead'),
                            ]),
                            Box::make('Мета-строка', [
                                Text::make('Уровни (жирный текст)', 'meta_levels_strong')
                                    ->hint('4 уровня'),
                                Text::make('Уровни (обычный текст)', 'meta_levels_rest')
                                    ->hint('8 модулей'),
                                Text::make('Локация (жирный текст)', 'meta_location_strong')
                                    ->hint('Санкт-Петербург'),
                                Text::make('Локация (обычный текст)', 'meta_location_rest')
                                    ->hint('выезд по России'),
                                Text::make('Статус (жирный текст)', 'meta_status')
                                    ->hint('Набор открыт'),
                            ]),
                        ])->icon('home'),

                        Tab::make('О программе', [
                            Box::make('Блок «О программе»', [
                                Text::make('Метка', 'about_eyebrow')
                                    ->hint('О программе'),
                                Text::make('Заголовок', 'about_title'),
                                TinyMce::make('Текст', 'about_text'),
                                Textarea::make('Цитата', 'about_quote')
                                    ->hint('Текст без кавычек — они добавляются автоматически'),
                                Text::make('Автор цитаты', 'about_quote_author')
                                    ->hint('— Василий Никольский, бизнес-тренер'),
                            ]),
                        ])->icon('chat-bubble-left'),

                        Tab::make('Структура', [
                            Box::make('Блок «Уровни подготовки»', [
                                Text::make('Метка', 'levels_eyebrow')
                                    ->hint('Структура программы'),
                                Text::make('Заголовок', 'levels_title'),
                                Textarea::make('Подзаголовок', 'levels_lead'),
                            ]),
                        ])->icon('list-bullet'),

                        Tab::make('Методология', [
                            Box::make('Блок «Как проходит обучение»', [
                                Text::make('Метка', 'method_eyebrow')
                                    ->hint('Методология'),
                                Text::make('Заголовок', 'method_title'),
                                Json::make('Карточки', 'method_cards')
                                    ->fields([
                                        Text::make('Заголовок', 'title'),
                                        Textarea::make('Описание', 'desc'),
                                    ])
                                    ->creatable(limit: 6)
                                    ->removable(),
                            ]),
                        ])->icon('academic-cap'),

                        Tab::make('Сайдбар', [
                            Box::make('Блок «Корпоративная версия»', [
                                Text::make('Заголовок', 'sidebar_corp_title'),
                                Textarea::make('Текст', 'sidebar_corp_text'),
                                Text::make('Текст кнопки', 'sidebar_corp_btn')
                                    ->hint('Обсудить'),
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
