@extends('layouts.layout')
<x-seo.meta-paginated :page="$page" :items="$items" />
@section('content')

<div class="content_page">
    <div class="container">
        @if($teaser_template !== \App\Enums\Resources\TeaserTemplate::Master)
            <div class="block_content__breadcrumbs">{{ Breadcrumbs::render(Route::currentRouteName()) }}</div>
        @endif
    </div>
    @if($items->count())
        @include($teaser_template->view($section), ['items' => $items, 'route' => $route])
    @endif

    @include($template->view($section))
</div>

@endsection
