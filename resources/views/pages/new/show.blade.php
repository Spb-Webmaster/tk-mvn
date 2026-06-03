@extends('layouts.layout')
<x-seo.meta
    title="{{ $item->metatitle ?: $item->title }}"
    description="{{ $item->description }}"
    keywords="{{ $item->keywords }}"
/>
@section('content')

<div class="news-page-header">
    <div class="news-ph-inner">
        <div class="block_content__breadcrumbs">{{ Breadcrumbs::render(Route::currentRouteName(), $item) }}</div>
        <h1 class="news-page-title">{{ $item->title }}</h1>
    </div>
</div>

@include($item->template->view($resource), ['item' => $item])

@endsection
