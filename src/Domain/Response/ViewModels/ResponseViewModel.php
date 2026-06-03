<?php

namespace Domain\Response\ViewModels;

use App\Models\Response;
use App\Models\ResponseCategory;
use App\Models\Setting;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Fluent;
use Support\Traits\Makeable;

class ResponseViewModel
{
    use Makeable;

    public function getPageData(): Fluent
    {
        return new Fluent(Setting::getGroup('response')->data ?? []);
    }

    public function getCategories(): \Illuminate\Database\Eloquent\Collection
    {
        return ResponseCategory::with(['responses' => function ($q) {
            $q->where('published', 1)->orderByDesc('created_at');
        }])
        ->orderBy('sorting')
        ->get()
        ->filter(fn($cat) => $cat->responses->isNotEmpty())
        ->values();
    }

    public function getPublished(): LengthAwarePaginator
    {
        return Response::published()->paginate(config('site.constants.paginate'));
    }

    public function getBySlug(string $slug): Response
    {
        return Response::published()->where('slug', $slug)->firstOrFail();
    }

    public function getRelated(Response $item, int $limit = 3): \Illuminate\Database\Eloquent\Collection
    {
        return Response::published()
            ->where('id', '!=', $item->id)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    public function getPrev(Response $item): ?Response
    {
        return Response::published()
            ->where('id', '<', $item->id)
            ->orderByDesc('id')
            ->first();
    }

    public function getNext(Response $item): ?Response
    {
        return Response::published()
            ->where('id', '>', $item->id)
            ->orderBy('id')
            ->first();
    }
}
