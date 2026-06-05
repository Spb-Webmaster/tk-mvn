<div class="training-page">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div class="ph-inner">
            <div class="ph-left">
                <div class="breadcrumb">
                    {{ Breadcrumbs::render(Route::currentRouteName(), $item) }}
                </div>

                <div class="ph-left-pad">
                    @if($item->subtitle)
                        <div class="page-eyebrow">{{ $item->subtitle }}</div>
                    @endif
                    @if($item->title)
                        <h1 class="page-title">{{ $item->title }}</h1>
                    @endif
                    @if($item->short_desc)
                        <div class="page-lead">{!! $item->short_desc !!}</div>
                    @endif
                    <div class="hdr-meta">
                        @if($item->ev_time || $item->ev_date_from)
                            <div class="hm">
                                <svg width="13" height="13" viewBox="0 0 14 14" fill="none" stroke="currentColor"
                                     stroke-width="1.5">
                                    <circle cx="7" cy="7" r="5.5"/>
                                    <path d="M7 4v3l2 1.5"/>
                                </svg>
                                @if($item->ev_time)
                                    <strong>{{ $item->ev_time }}</strong>
                                @endif
                                @if($item->ev_time && $item->ev_date_from)
                                    &nbsp;·&nbsp;
                                @endif
                                @if($item->ev_date_from)
                                    {{ $item->ev_duration_days }}
                                @endif
                            </div>
                        @endif
                        @if($item->ev_location)
                            <div class="hm">
                                <svg width="13" height="13" viewBox="0 0 14 14" fill="none" stroke="currentColor"
                                     stroke-width="1.5">
                                    <path
                                        d="M7 1.5C4.5 1.5 2.5 3.5 2.5 6c0 3.5 4.5 6.5 4.5 6.5S11.5 9.5 11.5 6c0-2.5-2-4.5-4.5-4.5z"/>
                                    <circle cx="7" cy="6" r="1.5"/>
                                </svg>
                                <strong>{{ $item->ev_location }}</strong>
                            </div>
                        @endif
                        @if($item->ev_price_individual || $item->ev_price_legal)
                            <div class="hm">
                                <svg width="13" height="13" viewBox="0 0 14 14" fill="none" stroke="currentColor"
                                     stroke-width="1.5">
                                    <rect x="1.5" y="2.5" width="11" height="9" rx="1"/>
                                    <path d="M5 1.5v3M9 1.5v3M1.5 6.5h11"/>
                                </svg>
                                @if($item->ev_price_individual)
                                    <strong>{{ price($item->ev_price_individual) }}
                                        &nbsp;{{ config('site.currency') }}</strong>
                                @endif
                                @if($item->ev_price_individual && $item->ev_price_legal)
                                    &nbsp;/&nbsp;
                                @endif
                                @if($item->ev_price_legal)
                                    {{ price($item->ev_price_legal) }}&nbsp;{{ config('site.currency') }} (юр.лицо)
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

            </div>
            <div class="date-box">
                <div class="date-box-lbl">Дата проведения</div>
                @if($item->ev_date_from)
                    <div class="date-box-main">{{ $item->ev_day_range }}</div>
                    <div class="date-box-month">{{ $item->ev_month_year }}</div>
                    <div class="date-box-div"></div>
                    <div class="date-box-sub">{{ $item->ev_duration_days }}@if($item->ev_time)
                            &nbsp;·&nbsp;{{ $item->ev_time }}
                        @endif</div>
                @else
                    <div class="date-box-month">по мере формирования группы</div>
                    <div class="date-box-div"></div>
                    <div class="date-box-sub">{{ date('Y') }}@if($item->ev_location)
                            &nbsp;·&nbsp;{{ $item->ev_location }}
                        @endif</div>
                @endif
            </div>
        </div>
    </div>

    <!-- MAIN LAYOUT -->
    <div class="page-wrap">
        <div class="main-col">

            <!-- О ПРОГРАММЕ -->
            @if($item->desc)
                <div class="about">
                    <div class="s-eye">О программе</div>
                    <div class="desc_blue">
                        {!! $item->desc !!}
                    </div>
                </div>
            @endif
            @if($item->desc2)
                <div class="about">
                    <div class="desc">
                        {!! $item->desc2 !!}
                    </div>
                </div>
            @endif

            <!-- ВИДЕО -->
            @if($item->video && count($item->video))
            @php
                $videos = collect($item->video)->filter(function($v) {
                    return !empty($v['url']) || !empty($v['rutube']) || !empty($v['file']);
                })->values();
            @endphp
            @if($videos->count())
            <div class="blk" id="vblk">
                <div class="mlbl">Видеоматериалы</div>
                <div class="vgrid" id="vg" data-count="{{ $videos->count() }}">
                    @foreach($videos as $video)
                    @php
                        if (!empty($video['url'])) {
                            preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $video['url'], $m);
                            $embedUrl = isset($m[1]) ? 'https://www.youtube.com/embed/' . $m[1] : null;
                        } elseif (!empty($video['rutube'])) {
                            preg_match('/rutube\.ru\/video\/([a-zA-Z0-9]+)/', $video['rutube'], $m);
                            $embedUrl = isset($m[1]) ? 'https://rutube.ru/play/embed/' . $m[1] . '/' : null;
                        } elseif (!empty($video['file'])) {
                            $embedUrl = Storage::url($video['file']);
                        } else {
                            $embedUrl = null;
                        }
                        $isRutube = !empty($video['rutube']);
                        $poster = !empty($video['poster']) ? Storage::url($video['poster']) : null;
                    @endphp
                    @if($embedUrl)
                    <div class="vi">
                        <a class="vph"
                           href="{{ $embedUrl }}{{ str_contains($embedUrl, '?') ? '&' : '?' }}autoplay=1{{ $isRutube ? '' : '&rel=0' }}"
                           data-fancybox="video-{{ $item->id }}"
                           data-type="iframe"
                           data-width="1280"
                           data-height="720"
                           @if($poster) style="background-image:url('{{ $poster }}');background-size:cover;background-position:center;" @endif>
                            <div class="vplay{{ $poster ? ' active' : '' }}">
                                <svg width="18" height="18" viewBox="0 0 20 20" fill="white">
                                    <polygon points="6,3 17,10 6,17"/>
                                </svg>
                            </div>
                            @if(!empty($video['title']))
                                <div class="vtxt">{{ $video['title'] }}</div>
                            @endif
                        </a>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            @endif
            @endif

            <!-- ФОТО -->
            @if($item->gallery && count($item->gallery))
            <div class="blk" id="iblk">
                <div class="mlbl">Фотоматериалы</div>
                <div class="igrid" id="ig" data-count="{{ count($item->gallery) }}">
                    @foreach($item->gallery as $photo)
                        @if(!empty($photo['image']))
                        <div class="ii">
                            <a href="{{ Storage::url($photo['image']) }}"
                               data-fancybox="gallery-{{ $item->id }}"
                               @if(!empty($photo['label'])) data-caption="{{ $photo['label'] }}" @endif>
                                <img src="{{ asset(intervention(config('thumbnail.training_gallery'), $photo['image'], 'content/gallery')) }}"
                                     alt="{{ $photo['label'] ?? '' }}"
                                     loading="lazy">
                            </a>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

            <!-- 4 БЛОКА -->
            @if(($item->ev_tasks && count($item->ev_tasks)) || auth('moonshine')->check())
            <x-admin.inline-edit :model="$item" field="ev_tasks" label="О тренинге" type="json">
            <div class="blk">
                <div class="s-eye">О тренинге</div>
                @if($item->ev_tasks && count($item->ev_tasks))
                <div class="info-blocks">
                    @foreach($item->ev_tasks as $task)
                    <div class="ib">
                        <div class="ib-n">{{ str_pad($loop->index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                        <div class="ib-t">{{ $task['title'] }}</div>
                        <div class="ib-d">{{ $task['value'] }}</div>
                    </div>
                    @endforeach
                </div>
                @else
                    <p style="opacity:0.4;font-size:13px;">— не заполнено —</p>
                @endif
            </div>
            </x-admin.inline-edit>
            @endif

            <!-- ЦЕЛИ -->
            @if(($item->ev_goals && count($item->ev_goals)) || auth('moonshine')->check())
            <x-admin.inline-edit :model="$item" field="ev_goals" label="Цели тренинга" type="json">
            <div class="blk">
                <div class="s-eye">Цели тренинга</div>
                @if($item->ev_goals && count($item->ev_goals))
                    @foreach($item->ev_goals as $goal)
                        @if(!empty($goal['title']))
                            <h2 class="s-ttl">{{ $goal['title'] }}</h2>
                        @endif
                        @if(!empty($goal['items']))
                            <ul class="cl">
                                @foreach($goal['items'] as $goalItem)
                                    <li>{{ $goalItem['value'] }}</li>
                                @endforeach
                            </ul>
                        @endif
                    @endforeach
                @else
                    <p style="opacity:0.4;font-size:13px;">— не заполнено —</p>
                @endif
            </div>
            </x-admin.inline-edit>
            @endif

            <!-- МОДУЛИ -->
            @if(($item->ev_modules && count($item->ev_modules)) || auth('moonshine')->check())
            <x-admin.inline-edit :model="$item" field="ev_modules" label="Модули тренинга" type="json">
            <div class="blk">
                <div class="s-eye">Структура программы</div>
                <h2 class="s-ttl">Модули тренинга</h2>
                @if($item->ev_modules && count($item->ev_modules))
                <div class="accordion">
                    @foreach($item->ev_modules as $module)
                    <div class="ai{{ $loop->first ? ' open' : '' }}">
                        <div class="ai-hdr" onclick="togAcc(this)">
                            <div class="ai-ttl">{{ $module['title'] }}</div>
                            <div class="ai-ico">+</div>
                        </div>
                        <div class="ai-body">{!! $module['value'] !!}</div>
                    </div>
                    @endforeach
                </div>
                @else
                    <p style="opacity:0.4;font-size:13px;">— не заполнено —</p>
                @endif
            </div>
            </x-admin.inline-edit>
            @endif

            <!-- МЕТОДЫ -->
            @if(($item->ev_methods && count($item->ev_methods)) || auth('moonshine')->check())
            <x-admin.inline-edit :model="$item" field="ev_methods" label="Методы ведения" type="json">
            <div class="blk">
                <div class="s-eye">Методы ведения</div>
                @if($item->ev_methods && count($item->ev_methods))
                    @foreach($item->ev_methods as $block)
                        @if(!empty($block['title']))
                            <h2 class="s-ttl">{{ $block['title'] }}</h2>
                        @endif
                        @if(!empty($block['items']))
                            <ul class="cl">
                                @foreach($block['items'] as $blockItem)
                                    <li>{{ $blockItem['value'] }}</li>
                                @endforeach
                            </ul>
                        @endif
                    @endforeach
                @else
                    <p style="opacity:0.4;font-size:13px;">— не заполнено —</p>
                @endif
            </div>
            </x-admin.inline-edit>
            @endif

            <!-- РЕЗУЛЬТАТЫ -->
            @if(($item->ev_results && count($item->ev_results)) || auth('moonshine')->check())
            <x-admin.inline-edit :model="$item" field="ev_results" label="Что получают участники" type="json">
            <div class="blk">
                <div class="s-eye">Что получают участники</div>
                @if($item->ev_results && count($item->ev_results))
                    @foreach($item->ev_results as $block)
                        @if(!empty($block['title']))
                            <h2 class="s-ttl">{{ $block['title'] }}</h2>
                        @endif
                        @if(!empty($block['items']))
                            <ul class="cl">
                                @foreach($block['items'] as $blockItem)
                                    <li>{{ $blockItem['value'] }}</li>
                                @endforeach
                            </ul>
                        @endif
                    @endforeach
                @else
                    <p style="opacity:0.4;font-size:13px;">— не заполнено —</p>
                @endif
            </div>
            </x-admin.inline-edit>
            @endif

            <!-- ЗАПИСЬ -->
            <x-training.registration
    :price-individual="$item->ev_price_individual"
    :price-legal="$item->ev_price_legal"
    :training-id="$item->id"
/>

        </div><!-- /main-col -->

        <!-- SIDEBAR -->
        <div class="sidebar">

            <div class="sb-date">
                <div class="sbd-lbl">Дата проведения</div>
                @if($item->ev_date_from)
                    <div class="sbd-main">{{ $item->ev_day_month }}</div>
                    <div class="sbd-yr">{{ $item->ev_date_from->year }} года@if($item->ev_location)
                            · {{ $item->ev_location }}
                        @endif</div>
                    <div class="sbd-rows">
                        <div class="sbd-row">Длительность <strong>{{ $item->ev_duration_days }}@if($item->ev_time)
                                    &nbsp;·&nbsp;{{ $item->ev_time }}
                                @endif</strong></div>
                        @if($item->ev_format)
                            <div class="sbd-row">Формат <strong>{{ $item->ev_format }}</strong></div>
                        @endif
                        @if($item->ev_location)
                            <div class="sbd-row">Место <strong>{{ $item->ev_location }}</strong></div>
                        @endif
                    </div>
                @else
                    <div class="sbd-yr" style="color: #ffffff; text-transform: uppercase; padding: 10px 0">По мере формирования группы</div>
                    <div class="sbd-yr">{{ date('Y') }} года@if($item->ev_location)
                            · {{ $item->ev_location }}
                        @endif</div>
                    <div class="sbd-rows">
                        @if($item->ev_format)
                            <div class="sbd-row">Формат <strong>{{ $item->ev_format }}</strong></div>
                        @endif
                        @if($item->ev_location)
                            <div class="sbd-row">Место <strong>{{ $item->ev_location }}</strong></div>
                        @endif
                    </div>
                @endif
            </div>

            <x-training.price-card
                :price-individual="$item->ev_price_individual"
                :price-legal="$item->ev_price_legal"
                note="Бронирование места — предоплата 50%. Место формируется по мере набора группы."
            />

        </div><!-- /sidebar -->
    </div><!-- /page-wrap -->

</div><!-- /training-page -->

<script>
    function togAcc(hdr) {
        hdr.parentElement.classList.toggle('open');
    }

</script>

@if($item->script)
    <script>{!! $item->script !!}</script>
@endif
