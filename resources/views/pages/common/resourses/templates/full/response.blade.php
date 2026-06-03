<div class="news-article-wrap">

    <main class="news-article-main">

        @if($item->img)
            <div class="response-article-author">
                <img src="{{ \Illuminate\Support\Facades\Storage::url($item->img) }}" alt="{{ $item->title }}" class="response-article-author__img">
                <div class="response-article-author__info">
                    <div class="response-article-author__name">{{ $item->title }}</div>
                    @if($item->subtitle)
                        <div class="response-article-author__position">{{ $item->subtitle }}</div>
                    @endif
                </div>
            </div>
        @else
            @if($item->subtitle)
                <p class="news-article-lead">{{ $item->subtitle }}</p>
            @endif
        @endif

        @if($item->desc)
            <div class="news-article-body desc">{!! $item->desc !!}</div>
        @endif

        @if($item->html)
            <div class="news-article-body">{!! $item->html !!}</div>
        @endif

        @if($item->desc2)
            <div class="news-article-body desc">{!! $item->desc2 !!}</div>
        @endif

        @if($item->html2)
            <div class="news-article-body">{!! $item->html2 !!}</div>
        @endif

        @if(!empty($item->gallery))
            <div class="news-gallery-section">
                <x-gallery.grid :items="$item->gallery" layout="article" />
            </div>
        @endif

        @if(!empty($item->files))
            <x-files.download :files="$item->files" />
        @endif

        @if(!empty($item->faq))
            <x-modules.faq :items="$item->faq" />
        @endif

    </main>

    <aside class="news-article-sidebar">
        <div class="news-cta-card">
            <div class="news-cta-card-eyebrow">Похожая программа</div>
            <div class="news-cta-card-title">Обсудить задачу для вашей компании</div>
            <div class="news-cta-card-text">Разработаем программу под цели и состав вашей команды руководителей.</div>
            <button class="btn-cta open-fancybox" data-form="zapros">Отправить запрос</button>
        </div>
    </aside>

</div>

@if(isset($prev) || isset($next))
<div class="article-pagination">
    <div class="pagination-inner">
        @if($prev)
        <a class="pag-link prev" href="{{ route('response.show', $prev->slug) }}">
            <span class="pag-direction">← Назад</span>
            <span class="pag-title">{{ $prev->title }}</span>
        </a>
        @else
        <div class="pag-link prev pag-empty"></div>
        @endif

        @if($next)
        <a class="pag-link next" href="{{ route('response.show', $next->slug) }}">
            <span class="pag-direction">Вперёд →</span>
            <span class="pag-title">{{ $next->title }}</span>
        </a>
        @else
        <div class="pag-link next pag-empty"></div>
        @endif
    </div>
</div>
@endif

@if($item->script)
    <script>{!! $item->script !!}</script>
@endif
