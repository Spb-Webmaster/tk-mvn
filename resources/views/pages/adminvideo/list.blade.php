@extends('layouts.layout')
@section('content')

<div class="av-page-header">
    <div class="av-ph-inner">
        <div class="block_content__breadcrumbs">
            <div class="breadcrumb">{{ Breadcrumbs::render(Route::currentRouteName()) }}</div>
        </div>
        <h1 class="page-title">{{ $page->title ?: 'Видео для администратора' }}</h1>
    </div>
</div>

@if($items->count())
    @include($teaser_template->view($section), ['items' => $items, 'route' => $route])
@endif

@include($template->view($section))

@endsection
