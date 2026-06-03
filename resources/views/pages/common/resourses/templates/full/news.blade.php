<div class="news-article-wrap">

    <main class="news-article-main">



        @if($item->subtitle)
        <p class="news-article-lead">{{ $item->subtitle }}</p>
        @endif

        @if($item->desc)
        <x-admin.inline-edit :model="$item" field="desc" label="Описание">
        <div class="news-article-body desc">
            {!! $item->desc !!}
        </div>
        </x-admin.inline-edit>
        @endif

        @if($item->html)
        <div class="news-article-body">
            {!! $item->html !!}
        </div>
        @endif

        @if($item->desc2)
        <div class="news-article-body desc">
            {!! $item->desc2 !!}
        </div>
        @endif

        @if($item->html2)
        <div class="news-article-body">
            {!! $item->html2 !!}
        </div>
        @endif

        @if(!empty($item->video))
            <div class="gallery-section">
                <div class="gallery-heading">Видео</div>
                @foreach($item->video as $video)
                    @if(!empty($video['url']) || !empty($video['rutube']) || !empty($video['file']))
                        <x-media.video
                            :src="$video['file'] ?? null"
                            :poster="$video['poster'] ?? null"
                            :url="$video['url'] ?? null"
                            :rutube="$video['rutube'] ?? null"
                        />
                    @endif
                @endforeach
            </div>
        @endif

        @if(!empty($item->gallery))
            <div class="news-gallery-section">
                <x-gallery.grid :items="$item->gallery"  layout="article" />
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

        @if(!empty($item->params))
        <div class="news-sidebar-card">
            <div class="news-sidebar-card-title">О мероприятии</div>
            @foreach($item->params as $label => $value)
            <div class="news-sidebar-fact">
                <span class="news-sidebar-fact-label">{{ $label }}</span>
                <span class="news-sidebar-fact-value">{{ $value }}</span>
            </div>
            @endforeach
        </div>
        @endif

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
        <a class="pag-link prev" href="{{ route('last-actions.show', $prev->slug) }}">
            <span class="pag-direction">← Назад</span>
            <span class="pag-title">{{ $prev->title }}</span>
        </a>
        @else
        <div class="pag-link prev pag-empty"></div>
        @endif

        @if($next)
        <a class="pag-link next" href="{{ route('last-actions.show', $next->slug) }}">
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
