@props([
    'src'    => null,
    'poster' => null,
    'url'    => null,
    'rutube' => null,
    'class'  => '',
])

<div class="media-video {{ $class }}">
    @if($url)
        @php
            preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $m);
            $embedSrc = isset($m[1]) ? 'https://www.youtube.com/embed/' . $m[1] . '?autoplay=1&rel=0' : null;
        @endphp
        @if($embedSrc)
            @if($poster)
                <div class="media-video__thumb" data-embed="{{ $embedSrc }}"
                     style="background-image: url('{{ Storage::url($poster) }}')">
                    <button class="media-video__play-btn" type="button" aria-label="Воспроизвести">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="white">
                            <polygon points="6,3 20,12 6,21"/>
                        </svg>
                    </button>
                </div>
            @else
                <div class="media-video__youtube-wrap">
                    <iframe class="media-video__youtube"
                            src="{{ $embedSrc }}"
                            allowfullscreen frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                    </iframe>
                </div>
            @endif
        @endif
    @elseif($rutube)
        @php
            preg_match('/rutube\.ru\/video\/([a-zA-Z0-9]+)/', $rutube, $m);
            $embedSrc = isset($m[1]) ? 'https://rutube.ru/play/embed/' . $m[1] . '/' : null;
        @endphp
        @if($embedSrc)
            @if($poster)
                <div class="media-video__thumb" data-embed="{{ $embedSrc }}"
                     style="background-image: url('{{ Storage::url($poster) }}')">
                    <button class="media-video__play-btn" type="button" aria-label="Воспроизвести">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="white">
                            <polygon points="6,3 20,12 6,21"/>
                        </svg>
                    </button>
                </div>
            @else
                <div class="media-video__youtube-wrap">
                    <iframe class="media-video__youtube"
                            src="{{ $embedSrc }}"
                            allowfullscreen frameborder="0"
                            allow="clipboard-write; autoplay">
                    </iframe>
                </div>
            @endif
        @endif
    @elseif($src)
        <video class="media-video__player" controls preload="metadata"
               @if($poster) poster="{{ Storage::url($poster) }}" @endif>
            <source src="{{ Storage::url($src) }}">
        </video>
    @endif
</div>
