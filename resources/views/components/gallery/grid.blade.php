@props([
    'items'   => [],
    'dir'     => 'content',
    'group'   => 'gallery',
    'thumb'   => '600x600',
    'layout'  => 'default',
    'heading' => 'Фотографии с мероприятия',
])

@if(!empty($items))
    @if($layout === 'article')
        <div class="gallery-section">
            @if($heading)
                <div class="gallery-heading">{{ $heading }}</div>
            @endif
            <div class="gallery-grid gallery-grid--article">
                @foreach($items as $i => $photo)
                    @php $src = is_array($photo) ? ($photo['image'] ?? null) : $photo; @endphp
                    @if(!empty($src))
                        <a class="gallery-item"
                           href="{{ Storage::url($src) }}"
                           data-fancybox="{{ $group }}">
                            <img loading="lazy"
                                 src="{{ asset(intervention($thumb, $src, $dir)) }}"
                                 alt="">
                            <div class="zoom-icon">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                                    <path d="M9 1h4v4M5 9L13 1M1 5V1h4M5 5L1 9" stroke="#152040" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    @else
        <div class="gallery-grid">
            @foreach($items as $photo)
                @php $src = is_array($photo) ? ($photo['image'] ?? null) : $photo; @endphp
                @if(!empty($src))
                    <a class="gallery-grid__item"
                       href="{{ Storage::url($src) }}"
                       data-fancybox="{{ $group }}">
                        <img class="gallery-grid__thumb"
                             loading="lazy"
                             src="{{ asset(intervention($thumb, $src, $dir)) }}"
                             alt="">
                    </a>
                @endif
            @endforeach
        </div>
    @endif
@endif
