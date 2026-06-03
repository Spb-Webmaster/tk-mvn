<?php

namespace App\Http\Controllers\New;

use App\Enums\Pages\PageTemplate;
use App\Enums\Resources\TeaserTemplate;
use App\Http\Controllers\Pages\PageController;
use App\Models\Setting;
use Domain\News\ViewModels\NewsViewModel;
use Illuminate\Contracts\View\View;

class NewController extends PageController
{
    public function list(): View
    {
        $vm       = NewsViewModel::make();
        $items    = $vm->getPublished();
        $page     = $vm->getPageData();
        $settings = Setting::getGroup('novosti')->data ?? [];

        $pageTemplate   = PageTemplate::tryFrom($settings['page_template'] ?? '')    ?? PageTemplate::News;
        $teaserTemplate = TeaserTemplate::tryFrom($settings['section_template'] ?? '') ?? TeaserTemplate::News;

        return view('pages.new.list', [
            'page'            => $page,
            'items'           => $items,
            'pageSuffix'      => $this->pageSuffix($items),
            'template'        => $pageTemplate,
            'teaser_template' => $teaserTemplate,
            'section'         => 'new',
            'route'           => 'last-actions.show',
        ]);
    }

    public function show(string $slug): View
    {
        $vm   = NewsViewModel::make();
        $item = $vm->getBySlug($slug);
        $page = $vm->getPageData();

        return view('pages.new.show', [
            'page'     => $page,
            'item'     => $item,
            'resource' => 'new',
            'prev'     => $vm->getPrev($item),
            'next'     => $vm->getNext($item),
        ]);
    }
}
