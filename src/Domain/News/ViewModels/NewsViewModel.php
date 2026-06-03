<?php

namespace Domain\News\ViewModels;

use App\Models\News;
use App\Models\Setting;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Fluent;
use Support\Traits\Makeable;

class NewsViewModel
{
    use Makeable;

    public function getPageData(): Fluent
    {
        return new Fluent(Setting::getGroup('novosti')->data ?? []);
    }

    public function getPublished(): LengthAwarePaginator
    {
        return News::published()->paginate(config('site.constants.paginate'));
    }

    public function getBySlug(string $slug): News
    {
        return News::published()->where('slug', $slug)->firstOrFail();
    }

    public function getRelated(News $item, int $limit = 3): \Illuminate\Database\Eloquent\Collection
    {
        return News::published()
            ->where('id', '!=', $item->id)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    public function getPrev(News $item): ?News
    {
        return News::published()
            ->where('id', '<', $item->id)
            ->orderByDesc('id')
            ->first();
    }

    public function getNext(News $item): ?News
    {
        return News::published()
            ->where('id', '>', $item->id)
            ->orderBy('id')
            ->first();
    }
}
