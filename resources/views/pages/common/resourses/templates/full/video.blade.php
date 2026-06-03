<hr>
<div class="news-article-wrap pad_t40_important">

    <main class="news-article-main">
        @if($item->title)
            <p class="news-article-lead">{{ $item->title }}</p>
        @endif
            @if($item->subtitle)
                <p class="">{{ $item->subtitle }}</p>
            @endif

        @if(!empty($item->video))
            <div class="video-article-players">
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
        @elseif($item->img)
            <div class="content_page__img">
                <x-picture.responsive
                    :sizes="['480x250', '768x400', '1200x630']"
                    :src="$item->img"
                    :alt="$item->title"
                />
            </div>
        @endif


        @if($item->desc)
        <x-admin.inline-edit :model="$item" field="desc" label="Описание">
            <div class="news-article-body desc">{!! $item->desc !!}</div>
        </x-admin.inline-edit>
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

        <x-modules.sb-card
            title="Нужна корпоративная программа?"
            desc="Разработаем тренинг под задачи и состав вашей команды. Выезд в любой город."
            :modal="true"
        />
        <br>
        <x-modules.stats2 class="white" />
        <br>
        <x-modules.master-nav />

    </aside>

</div>

@if(isset($prev) || isset($next))
<div class="article-pagination">
    <div class="pagination-inner">
        @if($prev)
        <a class="pag-link prev" href="{{ route('video.show', $prev->slug) }}">
            <span class="pag-direction">← Назад</span>
            <span class="pag-title">{{ $prev->title }}</span>
        </a>
        @else
        <div class="pag-link prev pag-empty"></div>
        @endif

        @if($next)
        <a class="pag-link next" href="{{ route('video.show', $next->slug) }}">
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
