<?php

namespace Domain\Video\ViewModels;

use App\Models\Video;
use App\Models\Setting;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Fluent;
use Support\Traits\Makeable;

class VideoViewModel
{
    use Makeable;

    public function getPageData(): Fluent
    {
        return new Fluent(Setting::getGroup('video')->data ?? []);
    }

    public function getPublished(): LengthAwarePaginator
    {
        return Video::published()->paginate(config('site.constants.paginate'));
    }

    public function getBySlug(string $slug): Video
    {
        return Video::published()->where('slug', $slug)->firstOrFail();
    }

    public function getRelated(Video $item, int $limit = 3): \Illuminate\Database\Eloquent\Collection
    {
        return Video::published()
            ->where('id', '!=', $item->id)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    public function getPrev(Video $item): ?Video
    {
        return Video::published()
            ->where('id', '<', $item->id)
            ->orderByDesc('id')
            ->first();
    }

    public function getNext(Video $item): ?Video
    {
        return Video::published()
            ->where('id', '>', $item->id)
            ->orderBy('id')
            ->first();
    }
}
