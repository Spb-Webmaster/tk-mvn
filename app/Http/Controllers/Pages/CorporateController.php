<?php

namespace App\Http\Controllers\Pages;

use App\Models\Response;
use App\Models\Setting;
use App\Models\Training;
use App\Models\TrainingCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class CorporateController extends PageController
{
    public function index(): View
    {
        $corporate = Setting::getGroup('corporate')->data ?? [];

        // Те же письма, что в блоке на главной — отмеченные галочкой «На главной»
        $letters = Response::published()
            ->lettersOnHome()
            ->get();

        return view('pages.corporate', [
            'corporate' => $corporate,
            'letters'   => $letters,
            'programs'  => $this->programs($corporate),
        ]);
    }

    /**
     * Карточки программ. В настройках хранится только ссылка на запись
     * («c:1» — раздел, «t:6» — отдельная программа), а заголовок, описание
     * и адрес подтягиваются из неё, если в карточке их не задали вручную.
     */
    private function programs(array $corporate): Collection
    {
        // Карточки, заведённые до появления отдельных программ, хранят
        // category_id вместо target — приводим их к общему виду
        $items = collect($corporate['programs'] ?? [])
            ->map(static function (array $item): array {
                if (empty($item['target']) && !empty($item['category_id'])) {
                    $item['target'] = 'c:' . $item['category_id'];
                }

                return $item;
            });

        $targets = $items->pluck('target')->filter();
        $categoryIds = $targets->filter(fn($t) => str_starts_with((string) $t, 'c:'))
            ->map(fn($t) => (int) substr($t, 2));
        $trainingIds = $targets->filter(fn($t) => str_starts_with((string) $t, 't:'))
            ->map(fn($t) => (int) substr($t, 2));

        $categories = $categoryIds->isNotEmpty()
            ? TrainingCategory::whereIn('id', $categoryIds)->get()->keyBy('id')
            : collect();

        $trainings = $trainingIds->isNotEmpty()
            ? Training::with('categories')->whereIn('id', $trainingIds)->get()->keyBy('id')
            : collect();

        return $items->map(function (array $item) use ($categories, $trainings): array {
            $target = (string) ($item['target'] ?? '');
            $title  = '';
            $desc   = '';
            $link   = trim($item['link'] ?? '');

            if (str_starts_with($target, 'c:') && ($category = $categories[(int) substr($target, 2)] ?? null)) {
                $title = $category->title;
                $desc  = $category->short_description ?? '';
                $link  = route('training.category.show', $category->slug);
            } elseif (str_starts_with($target, 't:') && ($training = $trainings[(int) substr($target, 2)] ?? null)) {
                $categorySlug = $training->categories->first()?->slug;
                $title = $training->title;
                $desc  = strip_tags($training->short_desc ?? '');
                $link  = $categorySlug
                    ? route('training.show', [$categorySlug, $training->slug])
                    : $link;
            }

            $item['title'] = trim($item['title'] ?? '') ?: $title;
            $item['desc']  = trim($item['desc'] ?? '') ?: $desc;
            $item['link']  = $link;

            return $item;
        })
        ->filter(fn(array $item) => $item['title'] !== '')
        ->values();
    }
}
