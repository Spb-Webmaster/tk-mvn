@extends('layouts.layout')
<x-seo.meta-paginated :page="$page" :items="$items" />
@section('content')

@if($items->count())
    <div class="content_page">
        <div class="container">
            @if($teaser_template !== \App\Enums\Resources\TeaserTemplate::Master)
                <div class="block_content__breadcrumbs">{{ Breadcrumbs::render(Route::currentRouteName()) }}</div>
            @endif
        </div>

        @include($teaser_template->view($section), ['items' => $items, 'route' => $route])

        @include($template->view($section))
    </div>
@else
    <x-modules.in-development
        :title="$page->title"
        :lead="$page->description"
        :desc="$page->desc"
    />
@endif

@endsection
