<section class="resp-section">
    <div class="resp-inner">

        @foreach($categories as $i => $cat)
            <div class="photo-tab-panel {{ $i === 0 ? 'active' : '' }}" id="photo-tab-{{ $cat->id }}">

                @php
                    $docItems   = $cat->responses->filter(fn($r) => !empty($r->img))->values();
                    $quoteItems = $cat->responses->reject(fn($r) => !empty($r->img))->values();
                @endphp

                {{-- Карточки документов (письма) --}}
                @if($docItems->isNotEmpty())
                    <div class="s-hdr">
                        <div class="s-eye">Документы</div>
                        <h3>Благодарственные письма</h3>
                    </div>
                    <div class="dc-grid">
                        @foreach($docItems as $item)
                            @php
                                $gallery    = collect($item->gallery ?? []);
                                $groupId    = $gallery->isNotEmpty()
                                                ? 'gallery-letter-' . $item->id
                                                : 'gallery-letters-' . $cat->id;
                                $mainHref   = Storage::url($item->img);
                            @endphp

                            <a href="{{ $mainHref }}"
                               data-fancybox="{{ $groupId }}"
                               data-caption="{{ e($item->title) }}"
                               class="dc card-hover">

                                <div class="dc-icon">
                                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none"><rect x="4" y="2" width="16" height="22" rx="1" fill="rgba(255,255,255,0.12)" stroke="var(--gold)" stroke-width="1.2"></rect><path d="M16 2v6h6" stroke="var(--gold)" stroke-width="1.2" fill="none"></path><path d="M8 11h10M8 15h10M8 19h6" stroke="rgba(255,255,255,0.4)" stroke-width="1.1" stroke-linecap="round"></path></svg>
                                </div>

                                <div class="dc-num">Письмо № {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
                                <div class="dc-title">Благодарственное письмо</div>
                                    <div class="dc-sub">{{ $item->title }}</div>
                                <div class="dc-open">Открыть документ →</div>

                            </a>

                            {{-- Скрытые ссылки для постраничной галереи --}}
                            @foreach($gallery as $gimg)
                                @php $gsrc = is_array($gimg) ? ($gimg['image'] ?? null) : $gimg; @endphp
                                @if($gsrc)
                                    <a href="{{ Storage::url($gsrc) }}"
                                       data-fancybox="{{ $groupId }}"
                                       style="display:none"></a>
                                @endif
                            @endforeach

                        @endforeach
                    </div>
                @endif

                {{-- Карточки цитат (отзывы) --}}
                @if($quoteItems->isNotEmpty())
                    <div class="s-hdr">
                        <div class="s-eye">Отзывы</div>
                        <h3>Что говорят участники</h3>
                    </div>
                    <div class="reviews-col">
                        @foreach($quoteItems as $item)
                            <div class="qc">
                                <span class="qmark">«</span>
                                <div class="qt-wrap collapsed">
                                    <div class="qt">{!! $item->desc !!}</div>
                                </div>
                                <button class="qmore" type="button" onclick="expandQ(this)">Читать полностью</button>
                                <div class="qa">
                                    <div class="qa-name">
                                        @if($item->short_desc )
                                            {!!  $item->short_desc !!}
                                        @else
                                            {!!  $item->title !!}
                                        @endif
                                    </div>
                                    @if($item->subtitle)
                                        <div class="qa-role">{{ $item->subtitle }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        @endforeach

    </div>
</section>

<script>
    (function () {
        function checkCollapsed(panel) {
            panel.querySelectorAll('.qt-wrap.collapsed').forEach(function (wrap) {
                if (wrap.scrollHeight <= wrap.clientHeight + 10) {
                    wrap.classList.remove('collapsed');
                    var btn = wrap.nextElementSibling;
                    if (btn && btn.classList.contains('qmore')) btn.style.display = 'none';
                }
            });
        }

        function activateTab(tabId) {
            document.querySelectorAll('.photo-tab-panel').forEach(function (p) {
                p.classList.remove('active');
            });
            var panel = document.getElementById(tabId);
            if (panel) {
                panel.classList.add('active');
                checkCollapsed(panel);
            }
        }

        document.querySelectorAll('.photo-tab-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.photo-tab-btn').forEach(function (b) {
                    b.classList.remove('active');
                });
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

        // Проверяем только активную панель при загрузке
        var activePanel = document.querySelector('.photo-tab-panel.active');
        if (activePanel) checkCollapsed(activePanel);

        window.expandQ = function (btn) {
            var wrap = btn.previousElementSibling;

            if (wrap.classList.contains('collapsed')) {
                wrap.style.maxHeight = wrap.scrollHeight + 'px';
                wrap.classList.remove('collapsed');
                btn.textContent = 'Свернуть';
                btn.classList.add('open');
                wrap.addEventListener('transitionend', function h(e) {
                    if (e.propertyName === 'max-height') {
                        wrap.style.maxHeight = '';
                        wrap.removeEventListener('transitionend', h);
                    }
                });
            } else {
                wrap.style.maxHeight = wrap.scrollHeight + 'px';
                wrap.getBoundingClientRect();
                wrap.style.maxHeight = '150px';
                wrap.classList.add('collapsed');
                btn.textContent = 'Читать полностью';
                btn.classList.remove('open');
                wrap.addEventListener('transitionend', function h(e) {
                    if (e.propertyName === 'max-height') {
                        wrap.style.maxHeight = '';
                        wrap.removeEventListener('transitionend', h);
                    }
                });
            }
        };
    }());
</script>
