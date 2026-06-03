@props([
    'title' => 'Нужна корпоративная версия?',
    'desc'=> "Адаптируем программу под специфику вашей отрасли. Выезд в любой город России.",
    'link'=> "Обсудить",
    'href' => "#reg",
    'modal' => false
])
<div class="sb-card sb-card--dark">
    <div class="sb-ttl">{{ $title  }}</div>
    <p>{{ $desc  }}</p>
    @if($modal)
        <button class="btn-cta open-fancybox" data-form="zapros">Отправить запрос</button>
    @else
    <a href="{{ $href }}" class="btn-f">{{ $link  }}</a>
    @endif
</div>
