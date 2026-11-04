<?php

namespace App\Http\Controllers\Pages;

use App\Models\Response;
use App\Models\Setting;
use App\Models\TrainingCategory;
use Illuminate\Contracts\View\View;

class CorporateController extends PageController
{
    public function index(): View
    {
        $corporate = Setting::getGroup('corporate')->data ?? [];

        // Карточки программ ссылаются на категории обучения по id.
        // Подгружаем их одним запросом: из категории берутся ссылка,
        // а также название и описание, если в карточке они не заданы.
        $categoryIds = collect($corporate['programs'] ?? [])
            ->pluck('category_id')
            ->filter()
            ->unique();

        $categories = $categoryIds->isNotEmpty()
            ? TrainingCategory::whereIn('id', $categoryIds)->get()->keyBy('id')
            : collect();

        // Те же письма, что в блоке на главной — отмеченные галочкой «На главной»
        $letters = Response::published()
            ->lettersOnHome()
            ->get();

        return view('pages.corporate', compact('corporate', 'letters', 'categories'));
    }
}
