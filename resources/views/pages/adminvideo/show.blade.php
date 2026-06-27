@extends('layouts.layout')
<x-seo.meta
    title="{{ $item->title }}"
    description=""
    keywords=""
/>
@section('content')

<div class="av-page-header">
    <div class="av-ph-inner">
        <div class="block_content__breadcrumbs">
            {{ Breadcrumbs::render(Route::currentRouteName(), $item) }}
        </div>
        <h1 class="av-page-title">{{ $item->title }}</h1>
    </div>
</div>

@include('pages.common.resourses.templates.full.adminvideo', ['item' => $item])

@endsection
