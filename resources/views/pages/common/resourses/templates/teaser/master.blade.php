@php $ms = $masterSettings ?? []; @endphp

<div class="training-page master-page">
    <div class="news-page-header pad_t0_important  pad_b0_important">

    <div class="news-ph-inner">
        <div class="block_content__breadcrumbs"><div class="breadcrumb">{{ Breadcrumbs::render(Route::currentRouteName()) }}</div></div>
        <div class="ph-inner pad_l0_important pad_r0_important ">
            <div class="ph-left">

                <div class="ph-left-pad">
                    <div class="page-eyebrow">{{ $ms['hero_eyebrow'] ?? 'Флагманская программа' }}</div>
                    <h1 class="page-title">{!! $ms['hero_title'] ?? 'Многоуровневая программа подготовки переговорщиков <br>«<em>Мастер коммуникаций</em>»' !!}</h1>
                    <p class="page-lead">{{ $ms['hero_lead'] ?? 'Системный курс развития переговорных компетенций — от основ делового общения до мастерства в жёстких коммуникациях.' }}</p>
                    <div class="hdr-meta">
                        <div class="hm">
                            <svg width="13" height="13" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="7" cy="7" r="5.5"/><path d="M7 4v3l2 1.5"/></svg>
                            <strong>{{ $ms['meta_levels_strong'] ?? '4 уровня' }}</strong>&nbsp;· {{ $ms['meta_levels_rest'] ?? '8 модулей' }}
                        </div>
                        <div class="hm">
                            <svg width="13" height="13" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M7 1.5C4.5 1.5 2.5 3.5 2.5 6c0 3.5 4.5 6.5 4.5 6.5S11.5 9.5 11.5 6c0-2.5-2-4.5-4.5-4.5z"/><circle cx="7" cy="6" r="1.5"/></svg>
                            <strong>{{ $ms['meta_location_strong'] ?? 'Санкт-Петербург' }}</strong>&nbsp;· {{ $ms['meta_location_rest'] ?? 'выезд по России' }}
                        </div>
                        <div class="hm">
                            <svg width="13" height="13" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="10" height="9" rx="1"/><path d="M5 1.5v3M9 1.5v3M2 6.5h10"/></svg>
                            <strong>{{ $ms['meta_status'] ?? 'Набор открыт' }}</strong>&nbsp;· {{ date("Y") }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




    <!-- MAIN LAYOUT -->
    <div class="page-wrap">
        <div class="main-col">

            <!-- О ПРОГРАММЕ -->
            <div class="about">
                <div class="s-eye">{{ $ms['about_eyebrow'] ?? 'О программе' }}</div>
                <h2 class="s-ttl">{{ $ms['about_title'] ?? 'Переговоры — это профессия' }}</h2>
                {!! $ms['about_text'] ?? '<p>Любой бизнес начинается и заканчивается за столом переговоров. От того, кто сядет за этот стол, зависит не только сохранение ресурсов организации, но и возможно судьба самого бизнеса и его собственников.</p><p>Программа «Мастер коммуникаций» — это поэтапный путь от базовых навыков делового общения до уровня опытного переговорщика, способного работать в жёстких условиях и управлять сложными коммуникациями любого уровня.</p>' !!}
                <div class="about-quote">
                    «{{ $ms['about_quote'] ?? 'Тренинг личная эффективность поможет научиться быть человеком, который всегда добивается поставленной цели.' }}»
                    <span>{{ $ms['about_quote_author'] ?? '— Василий Никольский, бизнес-тренер' }}</span>
                </div>
            </div>

            <!-- STRUCTURE -->
            <div class="blk">
                <div class="s-eye">{{ $ms['levels_eyebrow'] ?? 'Структура программы' }}</div>
                <h2 class="s-ttl">{{ $ms['levels_title'] ?? 'Уровни подготовки' }}</h2>
                <p class="blk-lead">{{ $ms['levels_lead'] ?? 'Каждый уровень — самостоятельный тренинг, который можно пройти отдельно. Вместе они составляют полный путь переговорщика.' }}</p>

                <div class="levels" id="levels">

                    @php
                        $iconPairs = [
                            // Уровень 1
                            [
                                '<path d="M9 2a5 5 0 100 10A5 5 0 009 2z"/><path d="M5 14h8M9 12v4"/>',
                                '<path d="M3 9h12M9 3l6 6-6 6"/>',
                            ],
                            // Уровень 2
                            [
                                '<circle cx="9" cy="9" r="7"/><path d="M6 9h6M9 6l3 3-3 3"/>',
                                '<path d="M9 2l2 5h5l-4 3 1.5 5L9 12l-4.5 3L6 10 2 7h5z"/>',
                            ],
                            // Уровень 3
                            [
                                '<circle cx="9" cy="7" r="3.5"/><path d="M4 16c0-3 2.2-5 5-5s5 2 5 5"/><path d="M14 8l2 2-2 2"/>',
                                '<path d="M9 2c-2.5 0-4 1.5-4 3.5 0 1.5 1 3 2.5 3.5L9 10l1.5-1C12 8.5 13 7 13 5.5 13 3.5 11.5 2 9 2z"/><path d="M6 14c0-2 1.3-3.5 3-3.5s3 1.5 3 3.5"/><line x1="9" y1="12" x2="9" y2="16"/>',
                            ],
                            // Уровень 4
                            [
                                '<circle cx="9" cy="9" r="7"/><circle cx="9" cy="9" r="3"/><line x1="9" y1="2" x2="9" y2="4"/><line x1="9" y1="14" x2="9" y2="16"/>',
                                '<path d="M11 2L5 10h5l-1 6 7-8H11z"/>',
                            ],
                        ];
                    @endphp

                    @foreach ($masterLevels as $level)
                    <div class="level-block" id="level-{{ $level->number }}">
                        <div class="level-header">
                            @if($level->alternative)
                            <div class="level-num-col level-num-col--alt">
                                <div class="level-num level-num--practice">{!! nl2br(e($level->alternative)) !!}</div>
                            </div>
                            @else
                            <div class="level-num-col">
                                <div class="level-label">Уровень</div>
                                <div class="level-num">{{ $level->number }}</div>
                            </div>
                            @endif
                            <div class="level-info-col">
                                <div class="level-tag">{{ $level->label ?: $level->title }}</div>
                                <div class="level-courses">

                                    @foreach ($level->trainings as $training)
                                    @php
                                        $categorySlug = $training->categories->first()?->slug ?? 'programma';
                                        $trainingUrl  = route('training.show', [$categorySlug, $training->slug]);
                                    @endphp
                                    <a class="level-course" href="{{ $trainingUrl }}">
                                        @php
                                            $pairIdx = ($level->number - 1) % count($iconPairs);
                                            $iconPath = $iconPairs[$pairIdx][$loop->index % 2];
                                        @endphp
                                        <div class="level-course-icon">
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="var(--gold)" stroke-width="1.5" stroke-linecap="round">{!! $iconPath !!}</svg>
                                        </div>
                                        <div class="level-course-body">
                                            <div class="level-course-title">{{ $training->title }}</div>
                                            <div class="level-course-desc">{{ \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($training->short_desc)), 120) }}</div>
                                            <div class="level-course-meta">
                                                @if($training->ev_date_from)
                                                    <span>{{ $training->ev_day_month }} {{ $training->ev_date_from->year }}</span>
                                                @endif
                                                @if($training->ev_duration_days)
                                                    <span>{{ $training->ev_duration_days }}</span>
                                                @endif
                                                @if($training->ev_price_individual || $training->ev_price_legal)
                                                    <span>
                                                        @if($training->ev_price_individual){{ number_format($training->ev_price_individual, 0, '.', ' ') }}&nbsp;₽@endif
                                                        @if($training->ev_price_individual && $training->ev_price_legal) / @endif
                                                        @if($training->ev_price_legal){{ number_format($training->ev_price_legal, 0, '.', ' ') }}&nbsp;₽@endif
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="level-course-arrow">›</div>
                                    </a>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div><!-- /levels -->
            </div>

            <!-- METHODOLOGY -->
            <div class="blk">
                <div class="s-eye">{{ $ms['method_eyebrow'] ?? 'Методология' }}</div>
                <h2 class="s-ttl">{{ $ms['method_title'] ?? 'Как проходит обучение' }}</h2>
                <div class="format-grid">
                    @php
                        $methodCards = $ms['method_cards'] ?? [
                            ['title' => 'Практика, а не лекция', 'desc' => '80% времени — упражнения, ролевые игры, кейсы участников. Тренер не читает — он тренирует.'],
                            ['title' => 'Малые группы',          'desc' => 'Оптимальный состав — 12 человек. Каждый участник получает личную обратную связь от тренера.'],
                            ['title' => 'Видеоразбор',           'desc' => 'Ролевые игры записываются и анализируются. Участник видит себя со стороны — это ускоряет рост.'],
                        ];
                        $methodIcons = [
                            '<rect x="4" y="6" width="24" height="20" rx="2"/><path d="M10 6V4M22 6V4M4 12h24"/><path d="M10 18h4M10 22h8"/>',
                            '<circle cx="16" cy="10" r="5"/><path d="M6 28c0-6 4.5-10 10-10s10 4 10 10"/><path d="M22 14l4 4-4 4"/>',
                            '<path d="M6 24l6-8 5 4 5-6 4 10"/><circle cx="8" cy="10" r="3"/>',
                        ];
                    @endphp
                    @foreach ($methodCards as $i => $card)
                    <div class="format-card card-hover">
                        <div class="format-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
                        <div class="format-icon">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" stroke="var(--gold)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">{!! $methodIcons[$i] ?? '' !!}</svg>
                        </div>
                        <div class="format-title">{{ $card['title'] }}</div>
                        <div class="format-desc">{{ $card['desc'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- REGISTRATION FORM -->
            <x-training.registration :price-individual="30000" :price-legal="36000" />
        </div><!-- /main-col -->

        <!-- SIDEBAR -->
        <div class="sidebar">

            @php $masterPrices = \App\Models\Setting::getGroup('constants')->data ?? []; @endphp
            <x-training.price-card
                :price-individual="$masterPrices['master_price_individual'] ?? null"
                :price-legal="$masterPrices['master_price_legal'] ?? null"
                note="За один модуль (2 дня). Для бронирования места — предоплата 50%."
            />

            <div class="sb-card">
                <div class="sb-nav-ttl pad_b15_important">Модули программы</div>
                <ul class="sb-nav">
                    @foreach ($masterLevels as $level)
                        @foreach ($level->trainings as $training)
                            @php
                                $sbCategorySlug = $training->categories->first()?->slug ?? 'programma';
                                $sbUrl = route('training.show', [$sbCategorySlug, $training->slug]);
                            @endphp
                            <li><a href="{{ $sbUrl }}">{{ $training->title }}</a></li>
                        @endforeach
                    @endforeach
                </ul>
            </div>
<x-modules.sb-card

    :title="$ms['sidebar_corp_title']"
    :desc="$ms['sidebar_corp_text']"
    :link="$ms['sidebar_corp_btn']"

/>


        </div><!-- /sidebar -->
    </div><!-- /page-wrap -->

</div><!-- /training-page master-page -->

<script>
    function masterToggle(id, courseEl) {
        const detail = document.getElementById(id);
        const isOpen = detail.classList.contains('open');
        const levelBlock = courseEl.closest('.level-block');
        levelBlock.querySelectorAll('.program-detail').forEach(d => d.classList.remove('open'));
        levelBlock.querySelectorAll('.level-course-arrow').forEach(a => a.textContent = '›');
        if (!isOpen) {
            detail.classList.add('open');
            courseEl.querySelector('.level-course-arrow').textContent = '‹';
        }
    }
</script>
