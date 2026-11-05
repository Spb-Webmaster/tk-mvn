@props([
    'title' => '',
    'body'  => '',
    'facts' => [],
])

@php
    // Цифры задаются в админке: Константы → Статистика → «Статистика (о тренере)»
    $facts = collect($facts)->filter(fn($f) => trim($f['num'] ?? '') !== '')->values();
@endphp

<section class="home-about" id="about">
    <div class="about-inner">
        <div class="about-photo">
                <img src="{{ asset('storage/images/home/04_4_2.jpg') }}" alt="Василий Никольский">
        </div>
        <div class="about-content">
            <div class="section-eyebrow eyebrow">О тренере</div>
            @if(!empty($title))
                <h2 class="section-title">{!! $title !!}</h2>
            @endif
            @if(!empty($body))
                <div class="about-body">{!! $body !!}</div>
            @endif
            @if($facts->isNotEmpty())
            <div class="about-facts">
                @foreach($facts as $fact)
                <div class="about-fact">
                    <div class="about-fact-num">{{ $fact['num'] }}</div>
                    @if(!empty($fact['label']))
                    <div class="about-fact-label">{{ $fact['label'] }}</div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
            <button class="btn-primary open-fancybox" data-form="zapros">Оставить запрос</button>
        </div>
    </div>
</section>
