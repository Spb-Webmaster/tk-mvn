<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\TrainingCategory;
use Illuminate\Database\Seeder;

/**
 * Начальное наполнение страницы «Компаниям» по ТЗ.
 *
 * Заполняет настройки только если группа `corporate` ещё пуста — чтобы
 * повторный запуск не затирал тексты, отредактированные в админке.
 */
class CorporatePageSeeder extends Seeder
{
    public function run(): void
    {
        $setting = Setting::getGroup('corporate');

        if (!empty($setting->data)) {
            $this->command?->info('Страница «Компаниям» уже заполнена — пропущено.');

            return;
        }

        $setting->data = [
            'menu_title' => 'Компаниям',

            'hero_eyebrow' => 'Корпоративное обучение',
            'hero_title'   => 'Развивайте людей.<br>Усиливайте бизнес.',
            'hero_lead'    => 'Практические бизнес-тренинги для руководителей и команд, чтобы эффективнее управлять, договариваться и решать рабочие задачи.',
            'hero_image'   => 'content/gallery/ycJlFFG6oLJ7pNiv5QQ5tHJHTelriIqJYpVDKfpx.jpg',
            'hero_points'  => [
                ['value' => 'Адаптированные программы под задачи вашей компании'],
                ['value' => 'Практика на реальных рабочих ситуациях'],
            ],
            'meta_years'   => '16 лет опыта',
            'meta_clients' => '80+ компаний-клиентов',
            'hero_btn'     => 'Обсудить задачу компании',
            'hero_note'    => 'Особые условия для корпоративных клиентов&nbsp;&nbsp;|&nbsp;&nbsp;Выезд по всей России',

            'formats_eyebrow' => 'Форматы работы',
            'formats_title'   => 'Выберите удобный формат',
            'formats_lead'    => '',
            'formats'         => [
                [
                    'title' => 'Обучение с бизнес-тренером на вашей площадке',
                    'desc'  => 'Тренер приезжает к вам: программа адаптируется под задачи компании и проходит на вашей территории, в удобное для команды время.',
                    'image' => 'content/gallery/S1A7dLdq3m8ReEsFOJ9HYfCCM6N2YyoFmYwQeE20.jpg',
                ],
                [
                    'title' => 'Мероприятие под ключ',
                    'desc'  => 'Обучение и комплексная организация: площадка, оборудование, кофе-брейки и сопровождение — вам остаётся только собрать участников.',
                    'image' => 'content/gallery/44s9p3K2nwstrwxbp5ZnAqXFSccrtucZFwThCOcW.jpg',
                ],
            ],

            'programs_eyebrow' => 'Программы',
            'programs_title'   => 'Выберите программу',
            'programs_lead'    => 'Каждая программа — это практика, а не лекция. Тренер не читает, он тренирует.',
            // Программы привязаны к категориям обучения: ссылка и — если поля
            // пустые — название с описанием подставляются из категории
            'programs' => $this->programs(),

            'metatitle'   => 'Корпоративное обучение для компаний — МВН, Санкт-Петербург',
            'description' => 'Практические бизнес-тренинги для руководителей и команд: переговоры, управление, развитие персонала. Программы под задачи компании, выезд по всей России.',
            'keywords'    => 'корпоративное обучение, бизнес-тренинги для компаний, обучение персонала, тренинги для руководителей',
        ];

        $setting->save();

        $this->command?->info('Страница «Компаниям» заполнена начальным контентом.');
    }

    /**
     * Карточки программ из ТЗ. Категорию ищем по slug — если её нет,
     * карточка всё равно создаётся, но без ссылки.
     */
    private function programs(): array
    {
        $ids = TrainingCategory::pluck('id', 'slug');

        $items = [
            ['slug' => 'master-kommunikatsij',                 'note' => '8 модулей, 4 уровня подготовки', 'desc' => 'Флагманская многоуровневая программа: от основ делового общения до мастерства в жёстких переговорах.'],
            ['slug' => 'programma-podgotovki-kadrovogo-rezerva', 'note' => '',                              'desc' => 'Подготовка руководителей нового поколения. Лидерство, управление командой, принятие решений.'],
            ['slug' => 'praktiki-razvitiia-personala',          'note' => '',                              'desc' => 'Стратегические сессии, ассессмент, консалтинг по построению системы обучения в организации. Системный подход к T&D.'],
        ];

        return array_map(static fn(array $item): array => [
            'target' => isset($ids[$item['slug']]) ? 'c:' . $ids[$item['slug']] : '',
            'title'  => '',
            'note'   => $item['note'],
            'desc'   => $item['desc'],
            'btn'    => 'Подробнее',
        ], $items);
    }
}
