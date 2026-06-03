@extends('layouts.layout')
<x-seo.meta-paginated :page="$page" :items="$items" />
@section('content')

    <div class="news-page-header no-after">
        <div class="news-ph-inner">
            <div class="block_content__breadcrumbs"><div class="breadcrumb">{{ Breadcrumbs::render(Route::currentRouteName()) }}</div></div>
            <div class="ph-inner">
                <div>
                    <div class="page-eyebrow">Мастерская Василия Никольского</div>
                    <h1 class="page-title page-title-600 pad_b19">{{ $page->title ?: 'Отзывы' }}</h1>
                    <p class="page-lead">{{ $page->short_desc }}</p>
                </div>
                <x-modules.stats2 :items="$constants['stats2'] ?? []" />
            </div>
        </div>
    </div>
    <div class="photo-tabs-wrap">
        <div class="photo-tabs-inner">
            @foreach($categories as $i => $cat)
                <button class="photo-tab-btn {{ $i === 0 ? 'active' : '' }}"
                        data-tab="photo-tab-{{ $cat->id }}">
                    {{ $cat->title }}
                </button>
            @endforeach
        </div>
        <div class="photo-tabs-select-wrap">
            <select class="photo-tabs-select">
                @foreach($categories as $cat)
                    <option value="photo-tab-{{ $cat->id }}">{{ $cat->title }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @if($categories->isNotEmpty())
        @include($teaser_template->view($section), ['items' => $items, 'route' => $route, 'categories' => $categories])
    @endif

    @include($template->view($section))

@endsection
