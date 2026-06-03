<section class="home-trainings home-section" id="trainings">
    <div class="container">
        <div class="section-head">
            <div class="s-eye">мероприятия</div>
            <h2 class="section-title">{{ $page->title }}</h2>
            <p class="section-lead">{!!  $page->description !!}</p>
        </div>
        <div class="events-grid">
            @foreach($items as $item)
            @php $itemCategory = $item->categories->first(); @endphp
            <div class="event-card card-hover">
                <div class="event-info">
                    <div class="event-tag">{{ $itemCategory?->title ?? '' }}</div>
                    <div class="event-title">{{ $item->title }}</div>
                    <div class="event-desc">{!! $item->short_desc !!}</div>
                    <a href="{{ route($route, [$itemCategory?->slug, $item->slug]) }}" class="event-link">Подробнее →</a>
                </div>
            </div>
            @endforeach
        </div>
        {{ $items->withQueryString()->links('pagination::default') }}
    </div>
</section>
