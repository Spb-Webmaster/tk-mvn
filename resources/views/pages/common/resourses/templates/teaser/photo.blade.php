<section class="photo-section">
    <div class="photo-inner">

        @foreach($categories as $i => $cat)
            <div class="photo-tab-panel {{ $i === 0 ? 'active' : '' }}" id="photo-tab-{{ $cat->id }}">
                <div class="albums">
                    @foreach($cat->photos as $item)
                        @php
                            $gallery  = collect($item->gallery ?? []);
                            $previews = $gallery->take(3)->values();
                            $total    = $gallery->count();

                            $previewSrc = function (int $idx) use ($previews, $item): ?string {
                                if ($previews->has($idx)) {
                                    $p = $previews->get($idx);
                                    return is_array($p) ? ($p['image'] ?? null) : $p;
                                }
                                return $item->img ?: null;
                            };
                        @endphp

                        <a href="{{ route($route, $item->slug) }}" class="album-card card-hover">

                            <div class="album-preview">

                                <div class="photo-main">
                                    @php $src = $previewSrc(0); @endphp
                                    @if($src)
                                        <img src="{{ asset(intervention('560x440', $src, 'content')) }}"
                                             loading="lazy" alt="{{ $item->title }}">
                                    @endif
                                </div>

                                <div class="photo-thumb">
                                    @php $src = $previewSrc(1); @endphp
                                    @if($src)
                                        <img src="{{ asset(intervention('280x220', $src, 'content')) }}"
                                             loading="lazy" alt="">
                                    @endif
                                </div>

                                <div class="photo-thumb">
                                    @php $src = $previewSrc(2); @endphp
                                    @if($src)
                                        <img src="{{ asset(intervention('280x220', $src, 'content')) }}"
                                             loading="lazy" alt="">
                                    @endif
                                </div>

                                <div class="album-overlay"></div>

                                @if($total)
                                    <div class="album-count">{{ $total }} фото</div>
                                @endif

                                @if($total > 3)
                                    <div class="album-see-all">Смотреть все →</div>
                                @endif

                            </div>

                            <div class="album-info">
                                <div class="album-title">{{ $item->title }}</div>
                                @if($item->short_desc)
                                    <div class="album-sub">{{ strip_tags($item->short_desc) }}</div>
                                @endif
                                <span class="album-link">Открыть альбом →</span>
                            </div>

                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach

    </div>
</section>

<script>
(function () {
    function activateTab(tabId) {
        document.querySelectorAll('.photo-tab-panel').forEach(function (p) { p.classList.remove('active'); });
        var panel = document.getElementById(tabId);
        if (panel) panel.classList.add('active');
    }

    document.querySelectorAll('.photo-tab-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.photo-tab-btn').forEach(function (b) { b.classList.remove('active'); });
            btn.classList.add('active');
            activateTab(btn.dataset.tab);
        });
    });

    var sel = document.querySelector('.photo-tabs-select');
    if (sel) {
        sel.addEventListener('change', function () {
            activateTab(this.value);
        });
    }
}());
</script>
