@extends('layouts.layout')
<x-seo.meta
    title="{{ $corporate['metatitle'] ?? 'Корпоративное обучение для компаний' }}"
    description="{{ $corporate['description'] ?? '' }}"
    keywords="{{ $corporate['keywords'] ?? '' }}"
/>
@section('content')

@php
    // Пустые поля не выводим — на сайте показывается только то, что заполнено в админке
    $heroImage = !empty($corporate['hero_image']) ? Storage::url($corporate['hero_image']) : null;
    $heroPoints = collect($corporate['hero_points'] ?? [])
        ->map(fn($p) => trim(is_array($p) ? ($p['value'] ?? '') : $p))
        ->filter()
        ->values();
    $formats = collect($corporate['formats'] ?? [])->filter(fn($f) => !empty($f['title']))->values();

    // Карточка программы берёт заголовок, описание и ссылку из выбранной
    // категории; заполненные вручную поля её перекрывают.
    $programs = collect($corporate['programs'] ?? [])
        ->map(function ($p) use ($categories) {
            $category = $categories[$p['category_id'] ?? null] ?? null;

            $p['title'] = trim($p['title'] ?? '') ?: ($category->title ?? '');
            $p['desc']  = trim($p['desc'] ?? '') ?: ($category->short_description ?? '');
            $p['link']  = $category
                ? route('training.category.show', $category->slug)
                : trim($p['link'] ?? '');

            return $p;
        })
        ->filter(fn($p) => $p['title'] !== '')
        ->values();
    $metaYears   = trim($corporate['meta_years'] ?? '');
    $metaClients = trim($corporate['meta_clients'] ?? '');
@endphp

<div class="corp-page">

    {{-- HERO --}}
    {{-- Сам фон и его затемнение описаны в SCSS, сюда приходит только путь к файлу --}}
    <section class="corp-hero @if($heroImage) corp-hero--image @endif"
             @if($heroImage) style="--corp-hero-bg: url('{{ $heroImage }}');" @endif>
        <div class="corp-hero__inner">
            <div class="block_content__breadcrumbs">
                <div class="breadcrumb">{{ Breadcrumbs::render(Route::currentRouteName()) }}</div>
            </div>

            @if(!empty($corporate['hero_eyebrow']))
                <div class="s-eye">{{ $corporate['hero_eyebrow'] }}</div>
            @endif

            @if(!empty($corporate['hero_title']))
                <h1 class="corp-hero__title">{!! $corporate['hero_title'] !!}</h1>
            @endif

            @if(!empty($corporate['hero_lead']))
                <p class="corp-hero__lead">{!! $corporate['hero_lead'] !!}</p>
            @endif

            @if($heroPoints->isNotEmpty())
                <ul class="corp-hero__points">
                    @foreach($heroPoints as $point)
                        <li>{!! $point !!}</li>
                    @endforeach
                </ul>
            @endif

            @if($metaYears !== '' || $metaClients !== '')
                <div class="corp-hero__meta">
                    @if($metaYears !== '')<strong>{{ $metaYears }}</strong>@endif
                    @if($metaYears !== '' && $metaClients !== '')<span class="corp-hero__meta-sep">·</span>@endif
                    @if($metaClients !== ''){{ $metaClients }}@endif
                </div>
            @endif

            @if(!empty($corporate['hero_btn']))
                <a href="#contact" class="btn-primary corp-hero__btn">{{ $corporate['hero_btn'] }}</a>
            @endif

            @if(!empty($corporate['hero_note']))
                <div class="corp-hero__note">{!! $corporate['hero_note'] !!}</div>
            @endif
        </div>
    </section>

    {{-- ФОРМАТЫ --}}
    @if($formats->isNotEmpty())
        <section class="corp-formats home-section" id="formats">
            <div class="container">
                @if(!empty($corporate['formats_eyebrow']) || !empty($corporate['formats_title']) || !empty($corporate['formats_lead']))
                    <div class="section-head">
                        @if(!empty($corporate['formats_eyebrow']))
                            <div class="s-eye">{{ $corporate['formats_eyebrow'] }}</div>
                        @endif
                        @if(!empty($corporate['formats_title']))
                            <h2 class="section-title">{{ $corporate['formats_title'] }}</h2>
                        @endif
                        @if(!empty($corporate['formats_lead']))
                            <p class="section-lead">{!! $corporate['formats_lead'] !!}</p>
                        @endif
                    </div>
                @endif

                <div class="corp-formats__grid">
                    @foreach($formats as $format)
                        <div class="corp-format card-hover">
                            @if(!empty($format['image']))
                                <div class="corp-format__media">
                                    <img src="{{ Storage::url($format['image']) }}" alt="{{ strip_tags($format['title']) }}" loading="lazy">
                                </div>
                            @endif
                            <div class="corp-format__body">
                                <div class="corp-format__num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
                                <div class="corp-format__title">{!! $format['title'] !!}</div>
                                @if(!empty($format['desc']))
                                    <div class="corp-format__desc">{!! $format['desc'] !!}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ПРОГРАММЫ --}}
    @if($programs->isNotEmpty())
        <section class="corp-programs home-section" id="programs">
            <div class="container">
                @if(!empty($corporate['programs_eyebrow']) || !empty($corporate['programs_title']) || !empty($corporate['programs_lead']))
                    <div class="section-head">
                        @if(!empty($corporate['programs_eyebrow']))
                            <div class="s-eye">{{ $corporate['programs_eyebrow'] }}</div>
                        @endif
                        @if(!empty($corporate['programs_title']))
                            <h2 class="section-title">{{ $corporate['programs_title'] }}</h2>
                        @endif
                        @if(!empty($corporate['programs_lead']))
                            <p class="section-lead">{!! $corporate['programs_lead'] !!}</p>
                        @endif
                    </div>
                @endif

                <div class="corp-programs__grid">
                    @foreach($programs as $program)
                        @php $link = trim($program['link'] ?? ''); @endphp
                        <{{ $link !== '' ? 'a' : 'div' }}
                            @if($link !== '') href="{{ $link }}" @endif
                            class="corp-program card-hover">

                            @if(!empty($program['image']))
                                <div class="corp-program__media">
                                    <img src="{{ Storage::url($program['image']) }}" alt="{{ strip_tags($program['title']) }}" loading="lazy">
                                </div>
                            @endif

                            <div class="corp-program__body">
                                <div class="corp-program__title">{!! $program['title'] !!}</div>
                                @if(!empty($program['note']))
                                    <div class="corp-program__note">{!! $program['note'] !!}</div>
                                @endif
                                @if(!empty($program['desc']))
                                    <div class="corp-program__desc">{!! $program['desc'] !!}</div>
                                @endif
                                @if($link !== '')
                                    <span class="corp-program__link">{{ $program['btn'] ?? 'Подробнее' }} →</span>
                                @endif
                            </div>
                        </{{ $link !== '' ? 'a' : 'div' }}>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- КЛИЕНТЫ --}}
    <x-modules.clients />

    {{-- БЛАГОДАРСТВЕННЫЕ ПИСЬМА --}}
    <x-modules.reviews :letters="$letters" />

    {{-- ФОРМА «ОБСУДИМ ВАШУ ЗАДАЧУ» --}}
    <x-modules.cta />

</div><!-- .corp-page -->
@endsection
