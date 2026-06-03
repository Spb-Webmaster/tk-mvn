@php
    $allItems  = $items->items();
    $firstPage = $items->currentPage() === 1;
    $featured  = ($firstPage && count($allItems) > 0) ? $allItems[0] : null;
    $grid      = $featured ? array_slice($allItems, 1) : $allItems;
@endphp
<section class="news-section">
    <div class="news-inner">

        @if($featured)
        <div class="news-featured">
            <div class="nf-new-badge">Последнее</div>
            <div class="news-featured-body">
                <h2 class="news-featured-title">{{ $featured->title }}</h2>
                @if($featured->short_desc)
                    <div class="news-featured-desc">{!! $featured->short_desc !!}</div>
                @endif
                <a href="{{ route($route, $featured->slug) }}" class="news-featured-link">
                    Читать подробнее →
                </a>
            </div>
            <div class="news-featured-aside">
                @if($featured->img)
                    <x-picture.responsive
                        :sizes="['360x336', '500x466', '700x653']"
                        :src="$featured->img"
                        :alt="$featured->title"
                    />
                @endif
            </div>
        </div>
        @endif

        @if(count($grid))
        <div class="news-grid">
            @foreach($grid as $item)
            <div class="news-card card-hover">
                @if($item->img)
                <div class="news-card__img">
                    <img src="{{ Storage::url($item->img) }}" alt="{{ $item->title }}">
                </div>
                @endif
                <div class="news-card__body">
                    <h3 class="news-card__title">
                        <a href="{{ route($route, $item->slug) }}">{{ $item->title }}</a>
                    </h3>
                    @if($item->short_desc)
                        <div class="news-card__desc">{!! $item->short_desc !!}</div>
                    @endif
                    <a href="{{ route($route, $item->slug) }}" class="news-card__link">Читать далее →</a>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <div class="news-pagination">
            {{ $items->withQueryString()->links('pagination::default') }}
        </div>

    </div>
</section>
