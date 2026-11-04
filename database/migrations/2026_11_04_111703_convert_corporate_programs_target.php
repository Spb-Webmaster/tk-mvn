<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

/**
 * В карточках программ теперь можно выбрать не только раздел обучения, но и
 * отдельную программу, поэтому вместо category_id хранится target с префиксом:
 * «c:1» — категория, «t:6» — тренинг.
 *
 * Переносим уже выбранные категории в новый формат.
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

        foreach ($data['programs'] as $i => $program) {
            if (!empty($program['target']) || empty($program['category_id'])) {
                continue;
            }

            $data['programs'][$i]['target'] = 'c:' . $program['category_id'];
            unset($data['programs'][$i]['category_id']);
        }

        $setting->data = $data;
        $setting->save();
    }

    public function down(): void
    {
        // Обратный разбор не требуется — прежние ссылки в карточках сохранены.
    }
};
