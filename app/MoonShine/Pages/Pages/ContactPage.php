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
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

class ContactPage extends Page
{
    public function getTitle(): string
    {
        return 'Контакты';
    }

    private function getSetting(): Setting
    {
        return Setting::getGroup('contact');
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

                        Tab::make('Контакты', [
                            Box::make('Заголовок страницы', [
                                Text::make('Заголовок h1', 'title')
                                    ->hint('Контакты'),
                            ]),
                            Box::make('Телефон', [
                                Text::make('Метка над телефоном', 'phone_label')
                                    ->hint('Телефон'),
                                Text::make('Номер телефона', 'phone')
                                    ->hint('+7 (812) 640-36-07'),
                                Text::make('Подпись под телефоном', 'phone_note')
                                    ->hint('Пн — Пт, 9:00 — 18:00'),
                            ]),
                            Box::make('Email и адрес', [
                                Text::make('E-mail', 'email')
                                    ->hint('info@tkmvn-spb.ru'),
                                Text::make('Адрес', 'address')
                                    ->hint('Санкт-Петербург'),
                                Text::make('Примечание к адресу', 'address_note')
                                    ->hint('Место проведения уточняется при записи'),
                            ]),
                            Box::make('Социальные сети', [
                                Text::make('ВКонтакте (URL)', 'vk_url')
                                    ->hint('https://vk.com/mbhgroup'),
                                Text::make('Telegram (URL)', 'telegram_url')
                                    ->hint('https://t.me/mbhgroup'),
                            ]),
                        ])->icon('phone'),

                        Tab::make('Реквизиты', [
                            Box::make('Организация', [
                                Text::make('Название организации', 'company_name')
                                    ->hint('ООО «МВН»'),
                                Text::make('ИНН', 'inn')
                                    ->hint('7804472366'),
                                Text::make('КПП', 'kpp')
                                    ->hint('780601001'),
                                Text::make('ОГРН', 'ogrn')
                                    ->hint('1117847474458'),
                                Text::make('ОКПО', 'okpo')
                                    ->hint('30670040'),
                                Text::make('ОКВЭД', 'okved')
                                    ->hint('74.1, 74.14, 74.5, 74.50, 74.50.2'),
                            ]),
                            Box::make('Адреса', [
                                Textarea::make('Юридический адрес', 'legal_address')
                                    ->hint('195279, г. Санкт-Петербург, шоссе Революции, дом 69, литера А, пом. 56Н, офис № 309'),
                                Textarea::make('Фактический адрес', 'actual_address')
                                    ->hint('195279, г. Санкт-Петербург, шоссе Революции, дом 69, литера А, пом. 56Н, офис № 309'),
                            ]),
                            Box::make('Банковские реквизиты', [
                                Text::make('Банк', 'bank_name')
                                    ->hint('СЕВЕРО-ЗАПАДНЫЙ БАНК ПАО СБЕРБАНК Г САНКТ-ПЕТЕРБУРГ'),
                                Text::make('БИК', 'bik')
                                    ->hint('044030653'),
                                Text::make('Корреспондентский счёт', 'cor_account')
                                    ->hint('30101810500000000653'),
                                Text::make('Расчётный счёт', 'settlement_account')
                                    ->hint('40702810455080008310'),
                            ]),
                            Box::make('Руководство и документы', [
                                Text::make('Генеральный директор', 'director')
                                    ->hint('Ярман В.Л.'),
                                Text::make('Ссылка на файл реквизитов', 'download_url')
                                    ->hint('URL для скачивания файла Word'),
                            ]),
                        ])->icon('building-office'),

                        Tab::make('Карта', [
                            Box::make('Яндекс.Карты', [
                                Divider::make('Embed-ссылка из Яндекс.Карт'),
                                Textarea::make('src для iframe', 'map_src')
                                    ->hint('https://yandex.ru/map-widget/v1/?ll=30.315868%2C59.939095&z=12&pt=30.315868,59.939095,pm2rdm'),
                            ]),
                        ])->icon('map-pin'),

                        Tab::make('Форма', [
                            Box::make('Форма обратной связи', [
                                Text::make('Метка над заголовком', 'form_eyebrow')
                                    ->hint('Обратная связь'),
                                Text::make('Заголовок формы', 'form_title')
                                    ->hint('Отправить запрос'),
                                Text::make('Подзаголовок формы', 'form_subtitle')
                                    ->hint('Ответим в течение одного рабочего дня.'),
                            ]),
                        ])->icon('envelope'),

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
