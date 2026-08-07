@props([
    'title'   => null,
    'lead'    => null,
    'desc'    => null,
    'eyebrow' => 'Мастерская Василия Никольского',
])

<div class="news-page-header no-after">
    <div class="news-ph-inner">
        <div class="block_content__breadcrumbs"><div class="breadcrumb">{{ Breadcrumbs::render(Route::currentRouteName()) }}</div></div>
        <div class="ph-inner">
            <div>
                <div class="page-eyebrow">{{ $eyebrow }}</div>
                <h1 class="page-title page-title-600 pad_b19">{{ $title }}</h1>
                @if($lead)
                    <p class="page-lead">{!! $lead !!}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<section class="in-dev">
    <div class="container">
        <div class="in-dev-box">
            <div class="in-dev-badge">Скоро</div>
            <div class="in-dev-title">Страница в стадии&nbsp;разработки</div>
            <p class="in-dev-text">Материалы этого раздела готовятся к публикации. Чтобы узнать о программе раньше остальных — напишите нам.</p>
            <a href="{{ route('contact') }}" class="in-dev-link">Связаться с нами</a>
        </div>

        @if($desc)
            <div class="desc">{!! $desc !!}</div>
        @endif
    </div>
</section>
