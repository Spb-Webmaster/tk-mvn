<section class="av-section">
    <div class="av-inner">
        <ul class="av-list">
            @foreach($items->items() as $item)
            <li class="av-item">
                <a href="{{ route($route, $item->slug) }}" class="av-link">
                    <span class="av-play-icon">
                        <svg width="10" height="12" viewBox="0 0 10 12" fill="none">
                            <path d="M1 1l8 5-8 5V1z" fill="currentColor"/>
                        </svg>
                    </span>
                    {{ $item->title }}
                </a>
            </li>
            @endforeach
        </ul>

        <div class="news-pagination">
            {{ $items->withQueryString()->links('pagination::default') }}
        </div>
    </div>
</section>
