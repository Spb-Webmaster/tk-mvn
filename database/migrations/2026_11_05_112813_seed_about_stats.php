<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

/**
 * Цифры в секции «О тренере» на главной были зашиты в шаблоне и не
 * редактировались — из-за этого правка «Статистики» в админке на них
 * не влияла. Теперь они берутся из констант, и сюда переносятся
 * прежние значения, чтобы блок выглядел как раньше.
 */
return new class extends Migration
{
    public function up(): void
    {
        $setting = Setting::getGroup('constants');
        $data    = $setting->data ?? [];

        if (!empty($data['stats_about'])) {
            return;
        }

        $data['stats_about'] = [
            ['num' => '500+', 'label' => 'проведённых тренингов'],
            ['num' => '80+',  'label' => 'компаний в портфеле'],
            ['num' => '16',   'label' => 'лет на рынке'],
            ['num' => '12',   'label' => 'человек в группе'],
        ];

        $setting->data = $data;
        $setting->save();
    }

    public function down(): void
    {
        // Настройки контента не откатываем.
    }
};
