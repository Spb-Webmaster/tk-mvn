@extends('layouts.layout')
<x-seo.meta-paginated :page="$page" :items="$items" />
@section('content')

{{-- HERO --}}
<section class="news-page-header no-after">
    <div class="news-ph-inner">
        <div class="block_content__breadcrumbs">
            <div class="breadcrumb">{{ Breadcrumbs::render(Route::currentRouteName()) }}</div>
        </div>
        <div class="ph-inner">
            <div>
                <div class="page-eyebrow">Мастерская Василия Никольского</div>
                <h1 class="page-title page-title-600 pad_b19">{{ $page->title ?: 'Видеоотчёты с мероприятий' }}</h1>
                @if($page->short_desc)
                <p class="page-lead">{{ $page->short_desc }}</p>
                @endif
            </div>
            <x-modules.stats2 :items="$constants['stats2'] ?? []" />
        </div>
    </div>
</section>

{{-- VIDEO GRID --}}
@include($teaser_template->view($section), ['items' => $items, 'route' => $route])

@endsection
