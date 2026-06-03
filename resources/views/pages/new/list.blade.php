@extends('layouts.layout')
<x-seo.meta-paginated :page="$page" :items="$items" />
@section('content')

<div class="news-page-header">
    <div class="news-ph-inner">
        <div class="block_content__breadcrumbs"><div class="breadcrumb">{{ Breadcrumbs::render(Route::currentRouteName()) }}</div></div>
        <div class="ph-inner">
            <div>
                <div class="page-eyebrow">Мастерская Василия Никольского</div>
                <h1 class="page-title page-title-600">{{ $page->title ?: 'Прошедшие мероприятия' }}</h1>
            </div>
            <x-modules.stats2 :items="$constants['stats2'] ?? []" />
        </div>
    </div>
</div>
@if($items->count())
    @include($teaser_template->view($section), ['items' => $items, 'route' => $route])
@endif

@include($template->view($section))

@endsection
