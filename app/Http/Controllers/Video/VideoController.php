<?php

namespace App\Http\Controllers\Video;

use App\Enums\Pages\PageTemplate;
use App\Enums\Resources\TeaserTemplate;
use App\Http\Controllers\Pages\PageController;
use App\Models\Setting;
use Domain\Video\ViewModels\VideoViewModel;
use Illuminate\Contracts\View\View;

class VideoController extends PageController
{
    public function list(): View
    {
        $vm       = VideoViewModel::make();
        $items    = $vm->getPublished();
        $page     = $vm->getPageData();
        $settings = Setting::getGroup('video')->data ?? [];

        $pageTemplate   = PageTemplate::tryFrom($settings['page_template'] ?? '')    ?? PageTemplate::News;
        $teaserTemplate = TeaserTemplate::tryFrom($settings['section_template'] ?? '') ?? TeaserTemplate::News;

        return view('pages.video.list', [
            'page'            => $page,
            'items'           => $items,
            'pageSuffix'      => $this->pageSuffix($items),
            'template'        => $pageTemplate,
            'teaser_template' => $teaserTemplate,
            'section'         => 'video',
            'route'           => 'video.show',
        ]);
    }

    public function show(string $slug): View
    {
        $vm   = VideoViewModel::make();
        $item = $vm->getBySlug($slug);
        $page = $vm->getPageData();

        return view('pages.video.show', [
            'page'     => $page,
            'item'     => $item,
            'resource' => 'video',
            'prev'     => $vm->getPrev($item),
            'next'     => $vm->getNext($item),
        ]);
    }
}
