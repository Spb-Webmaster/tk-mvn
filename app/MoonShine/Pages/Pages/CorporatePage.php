<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Pages;

use App\Models\Setting;
use App\Models\Training;
use App\Models\TrainingCategory;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use MoonShine\Crud\JsonResponse;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Support\Attributes\AsyncMethod;
use MoonShine\Support\Enums\ToastType;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Json;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;

class CorporatePage extends Page
{
    public function getTitle(): string
    {
        return 'Компаниям';
    }

    private function getSetting(): Setting
    {
        return Setting::getGroup('corporate');
    }

    #[AsyncMethod]
    public function store(Request $request): JsonResponse
    {
        $setting = $this->getSetting();
        $data = $request->except(['_token', '_method']);

        $data['formats']  = $this->storeJsonImages($request, 'formats', ['image'], $data['formats'] ?? [], 'content/corporate');
        $data['programs'] = $this->storeJsonImages($request, 'programs', ['image'], $data['programs'] ?? [], 'content/corporate');

        $heroImage = $this->storeSingleImage($request, 'hero_image', $data, 'content/corporate');

        if ($heroImage === null) {
            unset($data['hero_image']);
        } else {
            $data['hero_image'] = $heroImage;
        }

        // hidden_* — служебные поля MoonShine, в настройках им не место
        $data = array_filter($data, static fn (string $key): bool => ! str_starts_with($key, 'hidden_'), ARRAY_FILTER_USE_KEY);

        $setting->data = $data;
        $setting->save();

        return JsonResponse::make()->toast('Сохранено', ToastType::SUCCESS);
    }

    /**
     * $request->except() выше подхватывает только текстовые/чекбокс-поля —
     * загруженные файлы Laravel никогда не кладёт в input()/all(), поэтому
     * для вложенных Image-полей внутри Json(...) их нужно достать из
     * allFiles() и сохранить на диск вручную.
     *
     * Прежняя картинка берётся из скрытого поля hidden_<поле>, которое
     * MoonShine рендерит рядом с превью. Кнопка-крестик удаляет превью вместе
     * с этим скрытым полем, поэтому отсутствие hidden_<поле> — это команда
     * «картинку удалить». Брать прежнее значение из БД по индексу строки
     * нельзя: тогда удаление не срабатывает вовсе, а после удаления или
     * перестановки строк Json индексы разъезжаются и картинка может
     * «переехать» к соседней строке.
     */
    private function storeJsonImages(Request $request, string $jsonKey, array $imageFields, array $items, string $dir): array
    {
        foreach ($items as $index => &$item) {
            foreach ($imageFields as $field) {
                $keptValue = $item["hidden_$field"] ?? null;
                unset($item["hidden_$field"]);

                $file = $request->file("$jsonKey.$index.$field");

                if ($file instanceof UploadedFile) {
                    $item[$field] = $file->store($dir, 'public');
                    continue;
                }

                if (is_string($keptValue) && $keptValue !== '') {
                    $item[$field] = $keptValue;
                } else {
                    unset($item[$field]);
                }
            }
        }
        unset($item);

        return $items;
    }

    /**
     * То же самое, но для одиночного Image-поля вне Json: прежнее значение
     * лежит в $data['hidden_<поле>'], нет его — картинку удалили крестиком.
     */
    private function storeSingleImage(Request $request, string $field, array $data, string $dir): ?string
    {
        $file = $request->file($field);

        if ($file instanceof UploadedFile) {
            return $file->store($dir, 'public');
        }

        $keptValue = $data["hidden_$field"] ?? null;

        return is_string($keptValue) && $keptValue !== '' ? $keptValue : null;
    }

    /**
     * Список для выбора программы: разделы обучения и отдельные программы
     * в двух группах. Префикс в значении говорит, где искать запись:
     * «c:» — категория, «t:» — конкретный тренинг.
     */
    private function programOptions(): array
    {
        $categories = TrainingCategory::orderBy('sorting')
            ->pluck('title', 'id')
            ->mapWithKeys(static fn($title, $id) => ["c:$id" => $title])
            ->toArray();

        $trainings = Training::published()
            ->pluck('title', 'id')
            ->mapWithKeys(static fn($title, $id) => ["t:$id" => $title])
            ->toArray();

        return [
            'Разделы обучения'    => $categories,
            'Отдельные программы' => $trainings,
        ];
    }

    private function form(): FormBuilder
    {
        return FormBuilder::make()
            ->asyncMethod('store')
            ->fill($this->getSetting()->data ?? [])
            ->fields([
                Box::make([
                    Tabs::make([

                        Tab::make('Первый экран', [
                            Box::make('Hero-блок', [
                                Text::make('Метка над заголовком', 'hero_eyebrow')
                                    ->hint('Корпоративное обучение'),
                                Text::make('Заголовок h1', 'hero_title')
                                    ->hint('Допустим HTML. Например: Развивайте людей.<br>Усиливайте бизнес.'),
                                Textarea::make('Подзаголовок', 'hero_lead')->unescape(),
                                Image::make('Фоновое изображение', 'hero_image')
                                    ->disk('public')
                                    ->dir('content/corporate')
                                    ->allowedExtensions(['jpg', 'png', 'jpeg', 'webp'])
                                    ->removable()
                                    ->hint('Крупное фото с тренинга, горизонтальное'),
                            ]),
                            Box::make('Преимущества (список под подзаголовком)', [
                                Json::make('', 'hero_points')->fields([
                                    Text::make('Пункт', 'value')->unescape(),
                                ])->vertical()->creatable(limit: 6)->removable(),
                            ]),
                            Box::make('Строка опыта и кнопка', [
                                Text::make('Опыт (жирный текст)', 'meta_years')
                                    ->hint('16 лет опыта'),
                                Text::make('Клиенты (обычный текст)', 'meta_clients')
                                    ->hint('80+ компаний-клиентов'),
                                Text::make('Текст кнопки', 'hero_btn')
                                    ->hint('Обсудить задачу компании'),
                                Text::make('Примечание под кнопкой', 'hero_note')->unescape()
                                    ->hint('Особые условия для корпоративных клиентов | Выезд по всей России'),
                            ]),
                        ])->icon('home'),

                        Tab::make('Форматы', [
                            Box::make('Заголовок блока', [
                                Text::make('Метка', 'formats_eyebrow')
                                    ->hint('Форматы работы'),
                                Text::make('Заголовок', 'formats_title')
                                    ->hint('Выберите удобный формат'),
                                Textarea::make('Подзаголовок', 'formats_lead')->unescape(),
                            ]),
                            Box::make('Карточки форматов', [
                                Json::make('', 'formats')->fields([
                                    Text::make('Заголовок', 'title')->unescape(),
                                    Textarea::make('Описание', 'desc')->unescape(),
                                    Image::make('Изображение', 'image')
                                        ->disk('public')
                                        ->dir('content/corporate')
                                        ->allowedExtensions(['jpg', 'png', 'jpeg', 'webp'])
                                        ->removable(),
                                ])->vertical()->creatable(limit: 6)->removable(),
                            ]),
                        ])->icon('squares-2x2'),

                        Tab::make('Программы', [
                            Box::make('Заголовок блока', [
                                Text::make('Метка', 'programs_eyebrow')
                                    ->hint('Программы'),
                                Text::make('Заголовок', 'programs_title')
                                    ->hint('Выберите программу'),
                                Textarea::make('Подзаголовок', 'programs_lead')->unescape()
                                    ->hint('Каждая программа — это практика, а не лекция.'),
                            ]),
                            Box::make('Карточки программ', [
                                Json::make('', 'programs')->fields([
                                    Select::make('Программа', 'target')
                                        ->options(fn() => $this->programOptions())
                                        ->searchable()
                                        ->hint('Раздел целиком или отдельная программа — ссылка подставится автоматически'),
                                    Text::make('Заголовок', 'title')->unescape()
                                        ->hint('Если пусто — возьмётся название программы'),
                                    Textarea::make('Описание', 'desc')->unescape()
                                        ->hint('Если пусто — возьмётся краткое описание программы'),
                                    Text::make('Подпись под заголовком', 'note')->unescape()
                                        ->hint('8 модулей, 4 уровня подготовки'),
                                    Text::make('Текст кнопки', 'btn')
                                        ->hint('Подробнее'),
                                    Image::make('Изображение', 'image')
                                        ->disk('public')
                                        ->dir('content/corporate')
                                        ->allowedExtensions(['jpg', 'png', 'jpeg', 'webp'])
                                        ->removable(),
                                ])->vertical()->creatable(limit: 8)->removable(),
                            ]),
                        ])->icon('academic-cap'),

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
