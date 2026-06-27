<?php

namespace Domain\AdminVideo\ViewModels;

use App\Models\AdminVideo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Fluent;
use Support\Traits\Makeable;

class AdminVideoViewModel
{
    use Makeable;

    public function getPageData(): Fluent
    {
        return new Fluent([]);
    }

    public function getItems(): LengthAwarePaginator
    {
        return AdminVideo::ordered()->paginate(config('site.constants.paginate'));
    }

    public function getBySlug(string $slug): AdminVideo
    {
        return AdminVideo::where('slug', $slug)->firstOrFail();
    }

    public function getPrev(AdminVideo $item): ?AdminVideo
    {
        return AdminVideo::where('sorting', '<', $item->sorting)
            ->orderByDesc('sorting')
            ->first();
    }

    public function getNext(AdminVideo $item): ?AdminVideo
    {
        return AdminVideo::where('sorting', '>', $item->sorting)
            ->orderBy('sorting')
            ->first();
    }
}
