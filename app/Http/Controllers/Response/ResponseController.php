<?php

namespace App\Http\Controllers\Response;

use App\Enums\Pages\PageTemplate;
use App\Enums\Resources\TeaserTemplate;
use App\Http\Controllers\Pages\PageController;
use App\Models\Setting;
use Domain\Response\ViewModels\ResponseViewModel;
use Illuminate\Contracts\View\View;

class ResponseController extends PageController
{

    public function list(): View
    {
        $vm       = ResponseViewModel::make();
        $items    = $vm->getPublished();
        $page     = $vm->getPageData();
        $settings = Setting::getGroup('response')->data ?? [];

        $pageTemplate   = PageTemplate::tryFrom($settings['page_template'] ?? '')    ?? PageTemplate::News;
        $teaserTemplate = TeaserTemplate::tryFrom($settings['section_template'] ?? '') ?? TeaserTemplate::News;

        return view('pages.response.list', [
            'page'            => $page,
            'items'           => $items,
            'categories'      => $vm->getCategories(),
            'pageSuffix'      => $this->pageSuffix($items),
            'template'        => $pageTemplate,
            'teaser_template' => $teaserTemplate,
            'section'         => 'response',
            'route'           => 'response.show',
        ]);
    }

    public function show(string $slug): View
    {
        $vm   = ResponseViewModel::make();
        $item = $vm->getBySlug($slug);
        $page = $vm->getPageData();

        return view('pages.response.show', [
            'page'     => $page,
            'item'     => $item,
            'resource' => 'response',
            'prev'     => $vm->getPrev($item),
            'next'     => $vm->getNext($item),
        ]);
    }
}
