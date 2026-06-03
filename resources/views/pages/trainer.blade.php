@extends('layouts.layout')
<x-seo.meta
    title="{{ $trainer['metatitle'] ?? 'Василий Никольский — бизнес-тренер, консультант, коуч' }}"
    description="{{ $trainer['description'] ?? '' }}"
    keywords="{{ $trainer['keywords'] ?? '' }}"
/>
@section('content')

{{-- HERO --}}
<section class="profile-hero" id="top">
    <div class="profile-inner">

        <div class="profile-text">
            @if(!empty($trainer['hero_eyebrow']))
            <div class="hero-eyebrow s-eye">{{ $trainer['hero_eyebrow'] }}</div>
            @endif
            @if(!empty($trainer['hero_title']))
            <h1 class="profile-name">{!! $trainer['hero_title'] !!}</h1>
            @endif
            @if(!empty($trainer['hero_subtitle']))
            <p class="profile-role">{{ $trainer['hero_subtitle'] }}</p>
            @endif

            @if(!empty($trainer['hero_quote']))
            <div class="profile-quote">
                <p>{{ $trainer['hero_quote'] }}</p>
            </div>
            @endif

            @if(!empty($trainer['hero_desc']))
            <p class="profile-desc">{{ $trainer['hero_desc'] }}</p>
            @endif
            <div class="hero-actions">
                <button type="button" class="btn-primary open-fancybox" data-form="zapros">Отправить запрос</button>
                <a href="{{ route('schedule') }}" class="btn-outline-dark">Расписание тренингов</a>
            </div>
        </div>

        <div class="mosaic-col">
            <div class="mosaic-head">
                <span class="mh-label">Фотохроника</span>
                <span class="mh-rule"></span>
                <span class="mh-meta"><span class="mh-dot"></span>тренинги · форумы · практикумы</span>
            </div>
            <div class="mosaic-wrap">
                <div class="profile-mosaic">
                    <figure class="m-main">
                        <img src="{{ asset('storage/images/home/back_trener24.jpg') }}" alt="Василий Никольский" style="object-position: 73% 32%;">
                        <figcaption class="m-tag">
                            <b>Василий Никольский</b>
                            <span>Санкт-Петербург · с 2010 года</span>
                        </figcaption>
                    </figure>
                    <figure class="m-cell"><img src="{{ asset('storage/images/trainer/1.jpg') }}" alt="Мастер-класс" loading="lazy" style="object-position: center 22%;"><span class="m-label">Мастер-класс</span></figure>
                    <figure class="m-cell"><img src="{{ asset('storage/images/trainer/2.jpg') }}" alt="Бизнес-форум" loading="lazy" style="object-position: center 30%;"><span class="m-label">Бизнес-форум</span></figure>
                    <figure class="m-cell"><img src="{{ asset('storage/images/trainer/3.jpg') }}" alt="Тренинг" loading="lazy" style="object-position: center 18%;"><span class="m-label">Тренинг</span></figure>
                    <div class="m-info">
                        <svg width="56" height="40" viewBox="0 0 56 40" fill="none" aria-hidden="true">
                            <line x1="12" y1="10" x2="28" y2="20" stroke="#c4963a" stroke-width="1.4" stroke-opacity="0.85"/>
                            <line x1="45" y1="8" x2="28" y2="20" stroke="#c4963a" stroke-width="1.4" stroke-opacity="0.85"/>
                            <line x1="10" y1="32" x2="28" y2="20" stroke="#c4963a" stroke-width="1.4" stroke-opacity="0.85"/>
                            <line x1="46" y1="33" x2="28" y2="20" stroke="#c4963a" stroke-width="1.4" stroke-opacity="0.85"/>
                            <circle cx="28" cy="20" r="6" fill="#e6b95a"/>
                            <circle cx="12" cy="10" r="3.4" fill="#c4963a"/>
                            <circle cx="45" cy="8" r="3.4" fill="#c4963a"/>
                            <circle cx="10" cy="32" r="3.4" fill="#c4963a"/>
                            <circle cx="46" cy="33" r="3.4" fill="#c4963a"/>
                        </svg>
                        <div class="mi-t">
                            <b>Системный подход</b>
                            <span>к развитию бизнеса</span>
                        </div>
                    </div>
                    <figure class="m-cell"><img src="{{ asset('storage/images/trainer/4.jpg') }}" alt="Практикум" loading="lazy" style="object-position: center 30%;"><span class="m-label">Практикум</span></figure>
                    <figure class="m-cell"><img src="{{ asset('storage/images/trainer/5.jpg') }}" alt="Стратегическая сессия" loading="lazy" style="object-position: 60% 70%;"><span class="m-label">Стратег-сессия</span></figure>
                </div>
            </div>
        </div>

    </div>
</section>

{{-- STATS --}}
<x-modules.stats :items="$constants['stats'] ?? []" />

{{-- MAIN CONTENT --}}
<div class="trainer-page">
    <div class="trainer-page__main">

        {{-- BIO --}}
        <div class="trainer-bio">
            @if(!empty($trainer['bio_eyebrow']))
            <div class="s-eye">{{ $trainer['bio_eyebrow'] }}</div>
            @endif
            @if(!empty($trainer['bio_title']))
            <h2 class="trainer-bio__title">{{ $trainer['bio_title'] }}</h2>
            @endif
            @if(!empty($trainer['bio_text']))
            <div class="trainer-bio__text">{!! $trainer['bio_text'] !!}</div>
            @endif
        </div>

        {{-- DISTINCTIONS --}}
        @if(!empty($trainer['distinctions_items']))
        <div class="trainer-distinctions">
            @if(!empty($trainer['distinctions_eyebrow']))
            <div class="s-eye">{{ $trainer['distinctions_eyebrow'] }}</div>
            @endif
            @if(!empty($trainer['distinctions_title']))
            <h2 class="trainer-distinctions__title">{{ $trainer['distinctions_title'] }}</h2>
            @endif
            <div class="trainer-distinctions__grid">
                @foreach($trainer['distinctions_items'] as $item)
                <div class="trainer-distinctions__item">
                    <div class="trainer-distinctions__text">{{ $item['text'] ?? '' }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- PROGRAMS --}}
        @if(!empty($trainer['programs_items']))
        <div class="trainer-programs">
            @if(!empty($trainer['programs_eyebrow']))
            <div class="s-eye">{{ $trainer['programs_eyebrow'] }}</div>
            @endif
            @if(!empty($trainer['programs_title']))
            <h2 class="trainer-programs__title">{{ $trainer['programs_title'] }}</h2>
            @endif
            <div class="trainer-programs__grid">
                @foreach($trainer['programs_items'] as $item)
                <div class="trainer-programs__item">{{ $item['text'] ?? '' }}</div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- GALLERY --}}
{{--        <div class="trainer-gallery">
            <div class="s-eye">Фотогалерея</div>
            <h2 class="trainer-gallery__title">Тренинги и мероприятия</h2>
            <div class="trainer-gallery__grid">
                <div class="trainer-gallery__item"><img src="https://tkmvn-spb.ru/images/uploads/12_4.jpg" alt="Тренинг" loading="lazy"></div>
                <div class="trainer-gallery__item"><img src="https://tkmvn-spb.ru/images/uploads/14_4.jpg" alt="Тренинг" loading="lazy"></div>
                <div class="trainer-gallery__item"><img src="https://tkmvn-spb.ru/images/uploads/15_4.jpg" alt="Тренинг" loading="lazy"></div>
                <div class="trainer-gallery__item"><img src="https://tkmvn-spb.ru/images/uploads/16_4.jpg" alt="Тренинг" loading="lazy"></div>
                <div class="trainer-gallery__item"><img src="https://tkmvn-spb.ru/images/uploads/6_6.jpg" alt="Тренинг" loading="lazy"></div>
                <div class="trainer-gallery__item"><img src="https://tkmvn-spb.ru/images/uploads/9_1.jpg" alt="Тренинг" loading="lazy"></div>
                <div class="trainer-gallery__item"><img src="https://tkmvn-spb.ru/images/uploads/4_2.jpg" alt="Тренинг" loading="lazy"></div>
                <div class="trainer-gallery__item"><img src="https://tkmvn-spb.ru/images/uploads/3_7.jpg" alt="Тренинг" loading="lazy"></div>
            </div>
        </div>--}}

    </div>

    {{-- SIDEBAR --}}
    <aside class="trainer-sidebar">

        @if(!empty($trainer['sidebar_spec_items']))
        <div class="trainer-sidebar__card">
            <div class="trainer-sidebar__card-title">{{ $trainer['sidebar_spec_title'] ?? '' }}</div>
            <ul class="trainer-sidebar__list">
                @foreach($trainer['sidebar_spec_items'] as $item)
                <li>{{ $item['text'] ?? '' }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(!empty($trainer['sidebar_trainings_items']))
        <div class="trainer-sidebar__card">
            <div class="trainer-sidebar__card-title">{{ $trainer['sidebar_trainings_title'] ?? '' }}</div>
            <ul class="trainer-sidebar__list">
                @foreach($trainer['sidebar_trainings_items'] as $item)
                <li>{{ $item['text'] ?? '' }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="trainer-sidebar__contact" id="contact">
            @if(!empty($trainer['sidebar_contact_title']))
            <div class="trainer-sidebar__card-title">{{ $trainer['sidebar_contact_title'] }}</div>
            @endif
            @if(!empty($trainer['sidebar_contact_text']))
            <p>{{ $trainer['sidebar_contact_text'] }}</p>
            @endif
            @if(!empty($constants['contact_phone']))
            <a href="tel:{{ preg_replace('/\D/', '', $constants['contact_phone']) }}" class="trainer-sidebar__phone">{{ $constants['contact_phone'] }}</a>
            @endif
            <button class="btn-primary open-fancybox" data-form="zapros" style="width:100%">Отправить запрос</button>
        </div>

    </aside>

</div>

@endsection
