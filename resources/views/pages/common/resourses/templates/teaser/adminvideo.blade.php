<section class="av-section">
    <div class="av-inner">
        <ul class="av-list">
            @foreach($items->items() as $item)
            <li class="av-item">
                <a href="{{ route($route, $item->slug) }}" class="av-link">{{ $item->title }}</a>
            </li>
            @endforeach
        </ul>

        <div class="news-pagination">
            {{ $items->withQueryString()->links('pagination::default') }}
        </div>
    </div>
</section>
