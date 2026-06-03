<?php

namespace App\Http\Controllers\Photo;

use App\Enums\Pages\PageTemplate;
use App\Enums\Resources\TeaserTemplate;
use App\Http\Controllers\Pages\PageController;
use App\Models\Setting;
use Domain\Photo\ViewModels\PhotoViewModel;
use Illuminate\Contracts\View\View;

class PhotoController extends PageController
{
    public function list(): View
    {
        $vm       = PhotoViewModel::make();
        $items    = $vm->getPublished();
        $page     = $vm->getPageData();
        $settings = Setting::getGroup('photo')->data ?? [];

        $pageTemplate   = PageTemplate::tryFrom($settings['page_template'] ?? '')    ?? PageTemplate::News;
        $teaserTemplate = TeaserTemplate::tryFrom($settings['section_template'] ?? '') ?? TeaserTemplate::News;

        return view('pages.photo.list', [
            'page'            => $page,
            'items'           => $items,
            'categories'      => $vm->getCategories(),
            'pageSuffix'      => $this->pageSuffix($items),
            'template'        => $pageTemplate,
            'teaser_template' => $teaserTemplate,
            'section'         => 'photo',
            'route'           => 'photo.show',
        ]);
    }

    public function show(string $slug): View
    {
        $vm   = PhotoViewModel::make();
        $item = $vm->getBySlug($slug);
        $page = $vm->getPageData();

        return view('pages.photo.show', [
            'page'     => $page,
            'item'     => $item,
            'resource' => 'photo',
            'prev'     => $vm->getPrev($item),
            'next'     => $vm->getNext($item),
        ]);
    }
}
