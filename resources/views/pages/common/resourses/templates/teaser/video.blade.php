@php
    $allItems  = $items->items();
    $firstPage = $items->currentPage() === 1;
    $featured  = null;

    if ($firstPage && count($allItems) > 0) {
        $first = $allItems[0];
        if (!empty($first->video[0]['poster'])) {
            $featured = $first;
        }
    }

    $getEmbedUrl = function(array $videoData): ?string {
        if (!empty($videoData['rutube'])) {
            preg_match('/rutube\.ru\/video\/([^\/\?]+)/', $videoData['rutube'], $m);
            if (!empty($m[1])) return 'https://rutube.ru/play/embed/' . $m[1] . '/';
        }
        if (!empty($videoData['url'])) {
            preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\?\/]+)/', $videoData['url'], $m);
            if (!empty($m[1])) return 'https://www.youtube.com/embed/' . $m[1];
        }
        return null;
    };
@endphp

{{-- FEATURED — последнее видео --}}
@if($featured)
@php $featuredEmbed = isset($featured->video[0]) ? $getEmbedUrl($featured->video[0]) : null; @endphp
<div class="vsection">
    <div class="sec-head">
        <span class="sec-kicker">Последнее видео</span>
        <h2 class="sec-title">Свежая запись</h2>
        <span class="sec-rule"></span>
    </div>
    <article class="featured">
        @if($featuredEmbed)
        <a href="{{ $featuredEmbed }}"
           data-fancybox="video-{{ $featured->id }}"
           data-type="iframe"
           data-width="1280"
           data-height="720"
           class="featured-media">
        @else
        <a href="{{ route('video.show', $featured->slug) }}" class="featured-media">
        @endif
            <img src="{{ Storage::url($featured->video[0]['poster']) }}" alt="{{ e($featured->title) }}">
            <div class="playbadge">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
            </div>
        </a>
        <div class="featured-body">
            <span class="cat">{{ $featured->subtitle }}</span>
            <h3>{{ $featured->title }}</h3>
            {!!  $featured->short_desc !!}
            <a href="{{ route('video.show', $featured->slug) }}" class="watch">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
                Смотреть запись
            </a>
            <div class="venue">Мастерская Василия Никольского</div>
        </div>
    </article>
</div>
@endif

{{-- VIDEO GRID --}}
<section class="video-section">

    <div class="video-inner">
        <div class="sec-head">
            <span class="sec-kicker">Архив</span>
            <h2 class="sec-title">Все записи</h2>
            <span class="sec-rule"></span>
        </div>
        @php
            $grid = ($featured) ? array_slice($allItems, 1) : $allItems;
        @endphp

        @if(count($grid))
        <div class="vgrid">
            @foreach($grid as $item)
                @php
                    $poster   = $item->video[0]['poster'] ?? null;
                    $embedUrl = isset($item->video[0]) ? $getEmbedUrl($item->video[0]) : null;
                @endphp
                @if($poster)
                <article class="vcard card-hover">
                    @if($embedUrl)
                    <a href="{{ $embedUrl }}"
                       data-fancybox="video-{{ $item->id }}"
                       data-type="iframe"
                       data-width="1280"
                       data-height="720"
                       class="vcard-media">
                    @else
                    <a href="{{ route('video.show', $item->slug) }}" class="vcard-media">
                    @endif
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($poster) }}" alt="{{ e($item->title) }}" loading="lazy">
                        <div class="vcard-play">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                    </a>
                    <div class="vcard-body">
                        <h4><a href="{{ route('video.show', $item->slug) }}">{{ $item->title }}</a></h4>
                        @if($item->subtitle)
                        <div class="venue">{{ $item->subtitle }}</div>
                        @endif

                        <button class="copy-link" type="button" data-url="{{ route('video.show', $item->slug) }}">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            <span>Скопировать ссылку</span>
                        </button>

                    </div>
                </article>
                @endif
            @endforeach
        </div>
        @endif

        <div class="news-pagination">
            {{ $items->withQueryString()->links('pagination::default') }}
        </div>

    </div>
</section>

<script>
document.addEventListener('click', function (e) {
    var btn = e.target.closest('.copy-link');
    if (!btn) return;
    var url = btn.dataset.url;
    var span = btn.querySelector('span');
    navigator.clipboard.writeText(url).then(function () {
        span.textContent = 'Скопировано!';
        btn.dataset.copied = '1';
        setTimeout(function () {
            span.textContent = 'Скопировать ссылку';
            delete btn.dataset.copied;
        }, 2000);
    });
});
</script>
