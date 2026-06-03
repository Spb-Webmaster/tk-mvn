<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Pages;

use App\Models\Setting;
use Illuminate\Http\Request;
use MoonShine\Crud\JsonResponse;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Support\Attributes\AsyncMethod;
use MoonShine\Support\Enums\ToastType;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Divider;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\UI\Fields\Json;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;

class ConstantsPage extends Page
{
    public function getTitle(): string
    {
        return 'Константы';
    }

    private function getSetting(): Setting
    {
        return Setting::getGroup('constants');
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
                        Tab::make('Константы', [
                            Divider::make('Общие контактные данные сайта'),
                            Text::make('E-mail', 'contact_email')
                                ->hint('Основной email сайта'),
                            Text::make('Телефон', 'contact_phone')
                                ->hint('Например: +7 (812) 123-45-67'),
                        ])->icon('phone'),
                        Tab::make('Статистика', [
                            Divider::make('Блок цифр — выводится на главной и странице тренера'),
                            Json::make('Статистика', 'stats')->fields([
                                Text::make('Цифра', 'num')->hint('Например: 16 или 500+'),
                                Text::make('Подпись', 'label')->hint('Например: лет на рынке'),
                            ])->creatable()->removable(),
                            Divider::make('Блок цифр — выводится в шапке страницы мероприятий'),
                            Json::make('Статистика (мероприятия)', 'stats2')->fields([
                                Text::make('Цифра', 'num')->hint('Например: 500+ или 16'),
                                Text::make('Подпись', 'label')->hint('Например: мероприятий'),
                            ])->creatable()->removable(),
                        ])->icon('chart-bar'),
                        Tab::make('Стоимость', [
                            Divider::make('Программа «Мастер коммуникаций»'),
                            Number::make('Физ. лицо', 'master_price_individual')
                                ->hint('Стоимость для физических лиц, ₽'),
                            Number::make('Юр. лицо', 'master_price_legal')
                                ->hint('Стоимость для юридических лиц, ₽'),
                        ])->icon('currency-dollar'),
                        Tab::make('E-mail адреса', [
                            Divider::make('Получатели писем с форм сайта'),
                            Json::make('E-mail адреса', 'emails')->fields([
                                Text::make('E-mail', 'email')
                                    ->hint('Например: manager@domain.ru'),
                            ])->vertical()->creatable()->removable(),
                        ])->icon('envelope'),
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
