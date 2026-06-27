<?php

namespace App\Http\Controllers\AdminVideo;

use App\Enums\Pages\PageTemplate;
use App\Enums\Resources\TeaserTemplate;
use App\Http\Controllers\Pages\PageController;
use Domain\AdminVideo\ViewModels\AdminVideoViewModel;
use Illuminate\Contracts\View\View;

class AdminVideoController extends PageController
{
    public function list(): View
    {
        $vm    = AdminVideoViewModel::make();
        $items = $vm->getItems();
        $page  = $vm->getPageData();

        return view('pages.adminvideo.list', [
            'page'            => $page,
            'items'           => $items,
            'pageSuffix'      => $this->pageSuffix($items),
            'template'        => PageTemplate::Default,
            'teaser_template' => TeaserTemplate::AdminVideo,
            'section'         => 'adminvideo',
            'route'           => 'admin-video.show',
        ]);
    }

    public function show(string $slug): View
    {
        $vm   = AdminVideoViewModel::make();
        $item = $vm->getBySlug($slug);

        return view('pages.adminvideo.show', [
            'item' => $item,
            'prev' => $vm->getPrev($item),
            'next' => $vm->getNext($item),
        ]);
    }
}
