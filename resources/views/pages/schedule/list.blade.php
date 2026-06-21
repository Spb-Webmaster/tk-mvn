@extends('layouts.layout')
<x-seo.meta
    title="{{ $page->metatitle ?: ($page->title ?: 'Расписание тренингов') }}"
    description="{{ $page->description }}"
    keywords="{{ $page->keywords }}"
/>
@section('content')

@php
    $months = ['', 'Янв', 'Фев', 'Март', 'Апр', 'Май', 'Июнь', 'Июль', 'Авг', 'Сент', 'Окт', 'Нояб', 'Дек'];
@endphp

<div class="news-page-header">
    <div class="news-ph-inner">
        <div class="block_content__breadcrumbs"><div class="breadcrumb">{{ Breadcrumbs::render(Route::currentRouteName()) }}</div></div>
        <div class="ph-inner">
            <div>
                <div class="page-eyebrow">{{ now()->year }} · Санкт-Петербург</div>
                <h1 class="page-title page-title-600 pad_b19">{{ $page->title ?: 'Расписание тренингов' }}</h1>
                <p class="page-lead">{{ $page->short_desc ?: 'Открытые программы Мастерской Василия Никольского. Участие — для частных специалистов и корпоративных команд.' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="schedule-wrap">

    {{-- ── ЛЕВАЯ КОЛОНКА: СОБЫТИЯ ── --}}
    <div>

        <div class="events-list">
            @forelse($upcoming as $training)
                @php
                    $hasRange  = $training->ev_date_from && $training->ev_date_to && !$training->ev_date_from->isSameDay($training->ev_date_to);
                    $sameMonth = $hasRange && ($training->ev_date_from->month === $training->ev_date_to->month);
                    $catSlug   = $training->categories->first()?->slug;
                    $url       = $catSlug ? route('training.show', [$catSlug, $training->slug]) : '#';

                    $allGoals = collect($training->ev_goals ?? [])
                        ->flatMap(fn($b) => collect($b['items'] ?? [])->pluck('value'))
                        ->filter()
                        ->take(3);
                    $goalsTitle = $training->ev_goals[0]['title'] ?? 'Цели тренинга';
                @endphp
                <div class="event-card">

                    {{-- Колонка даты --}}
                    <div class="event-date-col">
                        @if($hasRange && $sameMonth)
                            <div class="event-day">{{ $training->ev_date_from->day }}</div>
                            <div class="event-range">—</div>
                            <div class="event-day2">{{ $training->ev_date_to->day }}</div>
                            <div class="event-month">{{ $months[$training->ev_date_from->month] }}</div>
                            <div class="event-year">{{ $training->ev_date_from->year }}</div>
                        @elseif($hasRange)
                            <div class="event-day-cross">{{ $training->ev_day_range }}</div>
                            <div class="event-year">{{ $training->ev_date_from->year }}</div>
                        @else
                            <div class="event-day">{{ $training->ev_date_from->day }}</div>
                            <div class="event-month">{{ $months[$training->ev_date_from->month] }}</div>
                            <div class="event-year">{{ $training->ev_date_from->year }}</div>
                        @endif
                    </div>

                    {{-- Тело карточки --}}
                    <div class="event-body">
                        <div class="event-tags">
                            @if($training->categories->isNotEmpty())
                                <span class="event-tag">{{ $training->categories->first()->title }}</span>
                            @endif
                            @if($training->ev_format)
                                <span class="event-tag format">{{ $training->ev_format }}</span>
                            @endif
                            @if($training->ev_duration_days)
                                <span class="event-tag format">{{ $training->ev_duration_days }}</span>
                            @endif
                        </div>

                        <a href="{{ $url }}" class="event-title">{{ $training->title }}</a>

                        <div class="event-meta">
                            <div class="event-meta-item">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="7" cy="7" r="5.5"/><path d="M7 4v3l2 1.5"/></svg>
                                {{ $training->ev_duration_days }}{{ $training->ev_time ? ' · ' . $training->ev_time : '' }}
                            </div>
                            @if($training->ev_location)
                                <div class="event-meta-item">
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M7 1.5C4.5 1.5 2.5 3.5 2.5 6c0 3.5 4.5 6.5 4.5 6.5s4.5-3 4.5-6.5c0-2.5-2-4.5-4.5-4.5z"/><circle cx="7" cy="6" r="1.5"/></svg>
                                    {{ $training->ev_location }}
                                </div>
                            @endif
                        </div>

                        @if($training->custom_field)
                            <div class="event-goals">
                            {!! $training->custom_field !!}
                            </div>
                        @else
                            @if($allGoals->isNotEmpty())
                                <div class="event-goals">
                                    <div class="event-goals-title">{{ $goalsTitle }}</div>
                                    <ul>
                                        @foreach($allGoals as $goal)
                                            <li>{{ $goal }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @endif

                    </div>

                    {{-- Колонка цены --}}
                    <div class="event-price-col">
                        @if($training->ev_price_individual)
                            <div class="price-group">
                                <div class="price-type">Для физ. лиц</div>
                                <div class="price-amount">{{ number_format($training->ev_price_individual, 0, '.', ' ') }} <span class="price-currency">₽</span></div>
                            </div>
                            <div class="price-divider"></div>
                        @endif
                        @if($training->ev_price_legal)
                            <div class="price-group">
                                <div class="price-type">Для юр. лиц</div>
                                <div class="price-amount">{{ number_format($training->ev_price_legal, 0, '.', ' ') }} <span class="price-currency">₽</span></div>
                            </div>
                            <div class="price-divider"></div>
                        @elseif(!$training->ev_price_individual)
                            <div class="price-group">
                                <div class="price-type">Стоимость</div>
                                <div class="price-amount price-amount--muted">По запросу</div>
                            </div>
                            <div class="price-divider"></div>
                        @endif
                        <a href="{{ $url }}" class="btn-details">Подробнее →</a>
                        <a href="{{ $url }}#reg" class="btn-register">Записаться</a>
                    </div>

                </div>
            @empty
                <div class="no-events">
                    <div class="no-events-title">Ближайших мероприятий нет</div>
                    Следите за обновлениями — программы публикуются регулярно.
                </div>
            @endforelse
        </div>

        {{-- Прошедшие мероприятия --}}
   {{--     <div class="section-divider">
            <div class="section-divider-line"></div>
            <div class="section-divider-label">Прошедшие мероприятия</div>
            <div class="section-divider-line"></div>
        </div>

        <div class="events-list">
            @if($past->isNotEmpty())
                @foreach($past as $training)
                    @php
                        $hasRange  = $training->ev_date_from && $training->ev_date_to && !$training->ev_date_from->isSameDay($training->ev_date_to);
                        $sameMonth = $hasRange && ($training->ev_date_from->month === $training->ev_date_to->month);
                    @endphp
                    <div class="event-card past">
                        <div class="event-date-col event-date-col--past">
                            @if($hasRange && $sameMonth)
                                <div class="event-day">{{ $training->ev_date_from->day }}</div>
                                <div class="event-range">—</div>
                                <div class="event-day2">{{ $training->ev_date_to->day }}</div>
                                <div class="event-month">{{ $months[$training->ev_date_from->month] }}</div>
                            @else
                                <div class="event-day">{{ $training->ev_date_from->day }}</div>
                                <div class="event-month">{{ $months[$training->ev_date_from->month] }}</div>
                            @endif
                        </div>
                        <div class="event-body">
                            <div class="event-tags">
                                @if($training->ev_format)
                                    <span class="event-tag format">{{ $training->ev_format }}</span>
                                @endif
                                @if($training->categories->isNotEmpty())
                                    <span class="event-tag format">{{ $training->categories->first()->title }}</span>
                                @endif
                            </div>
                            <div class="event-title" style="pointer-events:none;">{{ $training->title }}</div>
                            @if($training->ev_location)
                                <div class="event-meta">
                                    <div class="event-meta-item">{{ $training->ev_location }}</div>
                                </div>
                            @endif
                        </div>
                        <div class="event-price-col event-price-col--done">
                            <div class="event-done-label">Проведено</div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="event-card past">
                    <div class="event-date-col event-date-col--past">
                        <div class="event-day">14</div>
                        <div class="event-range">—</div>
                        <div class="event-day2">15</div>
                        <div class="event-month">Апр</div>
                    </div>
                    <div class="event-body">
                        <div class="event-tags">
                            <span class="event-tag format">Корпоративный</span>
                            <span class="event-tag format">Газпром Нефть</span>
                        </div>
                        <div class="event-title" style="pointer-events:none;">Переговорная практика для сотрудников регионального операционного центра</div>
                        <div class="event-meta">
                            <div class="event-meta-item">Тюмень · Газпромнефть-Снабжение</div>
                        </div>
                    </div>
                    <div class="event-price-col event-price-col--done">
                        <div class="event-done-label">Проведено</div>
                    </div>
                </div>
                <div class="event-card past">
                    <div class="event-date-col event-date-col--past">
                        <div class="event-day">18</div>
                        <div class="event-range">—</div>
                        <div class="event-day2">19</div>
                        <div class="event-month">Март</div>
                    </div>
                    <div class="event-body">
                        <div class="event-tags">
                            <span class="event-tag format">Корпоративный</span>
                            <span class="event-tag format">Газпром</span>
                        </div>
                        <div class="event-title" style="pointer-events:none;">Сессия по управлению качеством проектного портфеля</div>
                        <div class="event-meta">
                            <div class="event-meta-item">Санкт-Петербург · Газпром Межрегионгаз</div>
                        </div>
                    </div>
                    <div class="event-price-col event-price-col--done">
                        <div class="event-done-label">Проведено</div>
                    </div>
                </div>
            @endif
        </div>--}}

    </div>{{-- /левая колонка --}}

    {{-- ── ПРАВАЯ КОЛОНКА: САЙДБАР ── --}}
    <div class="schedule-sidebar">

        <x-modules.sb-card      :modal="true"         />


      {{--  <div class="schedule-sidebar__card">
            <div class="schedule-sidebar__card-title">Последние мероприятия</div>
            <div class="schedule-news-list">
                @if($past->isNotEmpty())
                    @foreach($past->take(3) as $item)
                        @php
                            $itemCat = $item->categories->first()?->slug;
                            $itemUrl = $itemCat ? route('training.show', [$itemCat, $item->slug]) : '#';
                        @endphp
                        <div class="schedule-news-item">
                            <a href="{{ $itemUrl }}">{{ $item->title }}</a>
                            <div class="schedule-news-item__meta">
                                {{ $item->ev_day_month }} {{ $item->ev_date_from?->year }}{{ $item->ev_location ? ' · ' . $item->ev_location : '' }}
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="schedule-news-item">
                        <a href="#">В Санкт-Петербурге проведена сессия по управлению качеством проектного портфеля для Газпром Межрегионгаз</a>
                        <div class="schedule-news-item__meta">Апрель 2026 · Санкт-Петербург</div>
                    </div>
                    <div class="schedule-news-item">
                        <a href="#">Переговорная практика для регионального операционного центра Газпромнефть-Снабжения</a>
                        <div class="schedule-news-item__meta">Март 2026 · Тюмень</div>
                    </div>
                    <div class="schedule-news-item">
                        <a href="#">FSI Газпром подвёл итоги образовательной программы под девизом «10 из 10»</a>
                        <div class="schedule-news-item__meta">Февраль 2026</div>
                    </div>
                @endif
            </div>
        </div>--}}

        <div class="schedule-sidebar__card">
            <div class="schedule-sidebar__card-title">Формат участия</div>
            <p>Открытые тренинги — для частных лиц и небольших команд (2–3 человека). Стоимость указана за одного участника.</p>
            <p>Корпоративный формат — выделенная группа от 8 человек. Программа адаптируется под специфику отрасли.</p>
        </div>

    </div>{{-- /сайдбар --}}

</div>{{-- /schedule-wrap --}}

@endsection
