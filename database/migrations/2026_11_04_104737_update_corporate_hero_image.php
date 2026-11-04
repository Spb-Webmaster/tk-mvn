<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

/**
 * Замена фонового изображения первого экрана страницы «Компаниям»
 * на подготовленный баннер.
 *
 * Меняем значение только если там всё ещё стоит картинка из начального
 * наполнения — чтобы не затереть изображение, загруженное через админку.
 * Сам файл в git не попадает (storage игнорируется) и заливается на сервер
 * отдельно.
 */
return new class extends Migration
{
    private const OLD_IMAGE = 'content/gallery/ycJlFFG6oLJ7pNiv5QQ5tHJHTelriIqJYpVDKfpx.jpg';
    private const NEW_IMAGE = 'content/corporate/corporate-hero.jpg';

    public function up(): void
    {
        $setting = Setting::getGroup('corporate');
        $data    = $setting->data ?? [];

        if (($data['hero_image'] ?? null) !== self::OLD_IMAGE) {
            return;
        }

        $data['hero_image'] = self::NEW_IMAGE;
        $setting->data = $data;
        $setting->save();
    }

    public function down(): void
    {
        // Контент страницы не откатываем.
    }
};
