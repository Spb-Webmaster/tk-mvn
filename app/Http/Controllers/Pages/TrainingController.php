<?php

namespace App\Http\Controllers\Pages;

use App\Enums\Pages\PageTemplate;
use App\Enums\Resources\TeaserTemplate;
use App\Models\Setting;
use App\Models\TrainingCategory;
use App\Models\TrainingLevel;
use Domain\Training\ViewModels\TrainingViewModel;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Fluent;

class TrainingController extends PageController
{
    public function index(): View
    {
        $vm    = TrainingViewModel::make();
        $items = $vm->getPublished();
        $page  = $vm->getPageData();

        $categories = TrainingCategory::orderBy('sorting')->get();

        return view('pages.training.categories', [
            'page' => $page,
        ]);
    }

    public function show(string $categorySlug, string $slug): View
    {
        $vm   = TrainingViewModel::make();
        $item = $vm->getBySlug($slug);
        $page = $vm->getPageData();

        return view('pages.training.show', [
            'page'     => $page,
            'item'     => $item,
            'resource' => 'training',
        ]);
    }

    public function indexCategoryShow(string $slug): View
    {
        $category = TrainingCategory::where('slug', $slug)->firstOrFail();
        $vm       = TrainingViewModel::make();
        $items    = $vm->getByCategory($category);

        $page = new Fluent([
            'title'       => $category->title,
            'desc'        => $category->description,
            'metatitle'   => $category->title,
            'description' => $category->short_description,
        ]);

        $categories     = TrainingCategory::orderBy('sorting')->get();
        $pageTemplate   = PageTemplate::Training;
        $teaserTemplate = $category->teaser_template ?? TeaserTemplate::Training;

        $viewData = [
            'page'            => $page,
            'items'           => $items,
            'categories'      => $categories,
            'pageSuffix'      => $this->pageSuffix($items),
            'template'        => $pageTemplate,
            'teaser_template' => $teaserTemplate,
            'section'         => 'training',
            'route'           => 'training.show',
        ];

        if ($teaserTemplate === TeaserTemplate::Master) {
            $viewData['masterLevels'] = TrainingLevel::with(['trainings' => function ($q) {
                $q->with('categories')->where('published', 1)->orderBy('sorting');
            }])
            ->whereBetween('number', [1, 4])
            ->orderBy('number')
            ->get()
            ->filter(fn($level) => $level->trainings->isNotEmpty());

            $viewData['masterSettings'] = Setting::getGroup('master')->data ?? [];
        }

        return view('pages.training.list', $viewData);
    }
}
