<?php

use App\Models\Setting;
use App\Models\TrainingCategory;
use Illuminate\Database\Migrations\Migration;

/**
 * Карточки программ на странице «Компаниям» теперь ссылаются на категорию
 * обучения через выпадающий список, а не через вручную вписанный адрес.
 *
 * Переносим уже заведённые карточки: находим категорию по slug из ссылки
 * и проставляем category_id. Поле link остаётся как есть — шаблон
 * использует его, если категория не выбрана.
 */
return new class extends Migration
{
    public function up(): void
    {
        $setting = Setting::getGroup('corporate');
        $data    = $setting->data ?? [];

        if (empty($data['programs'])) {
            return;
        }

        $categories = TrainingCategory::pluck('id', 'slug');

        foreach ($data['programs'] as $i => $program) {
            if (!empty($program['category_id']) || empty($program['link'])) {
                continue;
            }

            // Из «/trainings/master-kommunikatsij» или полного адреса берём slug
            $slug = basename(parse_url($program['link'], PHP_URL_PATH) ?: '');

            if ($slug !== '' && isset($categories[$slug])) {
                $data['programs'][$i]['category_id'] = (string) $categories[$slug];
            }
        }

        $setting->data = $data;
        $setting->save();
    }

    public function down(): void
    {
        // Ссылки в карточках остались на месте — откат не требуется.
    }
};
