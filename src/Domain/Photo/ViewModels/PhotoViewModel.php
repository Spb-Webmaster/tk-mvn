<?php

namespace Domain\Photo\ViewModels;

use App\Models\Photo;
use App\Models\PhotoCategory;
use App\Models\Setting;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Fluent;
use Support\Traits\Makeable;

class PhotoViewModel
{
    use Makeable;

    public function getPageData(): Fluent
    {
        return new Fluent(Setting::getGroup('photo')->data ?? []);
    }

    public function getPublished(): LengthAwarePaginator
    {
        return Photo::published()->paginate(config('site.constants.paginate'));
    }

    public function getCategories(): \Illuminate\Database\Eloquent\Collection
    {
        return PhotoCategory::with(['photos' => function ($q) {
            $q->where('published', 1)->orderByDesc('created_at');
        }])
        ->orderBy('sorting')
        ->get()
        ->filter(fn($cat) => $cat->photos->isNotEmpty())
        ->values();
    }

    public function getBySlug(string $slug): Photo
    {
        return Photo::published()->where('slug', $slug)->firstOrFail();
    }

    public function getRelated(Photo $item, int $limit = 3): \Illuminate\Database\Eloquent\Collection
    {
        return Photo::published()
            ->where('id', '!=', $item->id)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    public function getPrev(Photo $item): ?Photo
    {
        return Photo::published()
            ->where('id', '<', $item->id)
            ->orderByDesc('id')
            ->first();
    }

    public function getNext(Photo $item): ?Photo
    {
        return Photo::published()
            ->where('id', '>', $item->id)
            ->orderBy('id')
            ->first();
    }
}
