<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

/**
 * В секции «О тренере» осталось 500+ проведённых тренингов — значение
 * перешло из прежнего зашитого в шаблон блока, тогда как в основной
 * статистике стоит 200+. Приводим к одному числу.
 *
 * Меняем только если там всё ещё 500+ — выставленное вручную не трогаем.
 */
return new class extends Migration
{
    public function up(): void
    {
        $setting = Setting::getGroup('constants');
        $data    = $setting->data ?? [];

        if (empty($data['stats_about'])) {
            return;
        }

        foreach ($data['stats_about'] as $i => $item) {
            if (($item['num'] ?? '') === '500+') {
                $data['stats_about'][$i]['num'] = '200+';
            }
        }

        $setting->data = $data;
        $setting->save();
    }

    public function down(): void
    {
        // Значения статистики не откатываем.
    }
};
