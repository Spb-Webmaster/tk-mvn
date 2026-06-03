@extends('layouts.layout')
<x-seo.meta
    title="{{ $item->metatitle ?: $item->title }}"
    description="{{ $item->description }}"
    keywords="{{ $item->keywords }}"
/>
@section('content')

    @if($item->template === \App\Enums\Resources\FullTemplate::Training)
        @include($item->template->view($resource), ['item' => $item])
    @else
        <div class="content_page pad_b0_important">
            <div class="news-ph-inner">
                <div class="block_content__breadcrumbs">{{ Breadcrumbs::render(Route::currentRouteName(), $item) }}</div>

                @if($item->template === \App\Enums\Resources\FullTemplate::Default)
                    @if($item->title)
                        <h1 class="h1">{{ $item->title }}</h1>
                    @endif
                    @if($item->subtitle)
                        <div class="content_page__subtitle">{{ $item->subtitle }}</div>
                    @endif
                @endif
            </div>
                @include($item->template->view($resource), ['item' => $item])
        </div>
    @endif

@endsection
