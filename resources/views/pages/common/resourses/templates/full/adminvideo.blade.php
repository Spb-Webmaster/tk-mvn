<div class="av-article-wrap">
    <main class="av-article-main">

        @if($item->video)
        <div class="av-video-player">
            <video controls preload="metadata">
                <source src="{{ Storage::url($item->video) }}" type="video/mp4">
            </video>
        </div>
        @endif

        @if($item->desc)
        <div class="av-article-body desc">
            {!! $item->desc !!}
        </div>
        @endif

    </main>
</div>

@if(isset($prev) || isset($next))
<div class="article-pagination">
    <div class="pagination-inner">
        @if($prev)
        <a class="pag-link prev" href="{{ route('admin-video.show', $prev->slug) }}">
            <span class="pag-direction">← Назад</span>
            <span class="pag-title">{{ $prev->title }}</span>
        </a>
        @else
        <div class="pag-link prev pag-empty"></div>
        @endif

        @if($next)
        <a class="pag-link next" href="{{ route('admin-video.show', $next->slug) }}">
            <span class="pag-direction">Вперёд →</span>
            <span class="pag-title">{{ $next->title }}</span>
        </a>
        @else
        <div class="pag-link next pag-empty"></div>
        @endif
    </div>
</div>
@endif
