@props(['letters' => collect()])

@if($letters->isNotEmpty())
<section class="home-reviews home-section" id="reviews">
  <div class="container">
    <div class="section-head">
      <div class="s-eye">Отзывы</div>
      <h2 class="section-title">Благодарственные письма</h2>
    </div>

    <div class="dc-grid">
      @foreach($letters as $letter)
      @php
          $gallery = collect($letter->gallery ?? []);
          $groupId = $gallery->isNotEmpty() ? 'gallery-home-letter-' . $letter->id : 'gallery-home-letters';
      @endphp

      <a href="{{ Storage::url($letter->img) }}"
         data-fancybox="{{ $groupId }}"
         data-caption="{{ e($letter->title) }}"
         class="dc card-hover">

        <div class="dc-icon">
          <svg width="28" height="28" viewBox="0 0 28 28" fill="none"><rect x="4" y="2" width="16" height="22" rx="1" fill="rgba(255,255,255,0.12)" stroke="var(--gold)" stroke-width="1.2"></rect><path d="M16 2v6h6" stroke="var(--gold)" stroke-width="1.2" fill="none"></path><path d="M8 11h10M8 15h10M8 19h6" stroke="rgba(255,255,255,0.4)" stroke-width="1.1" stroke-linecap="round"></path></svg>
        </div>

        <div class="dc-num">Благодарность</div>
        <div class="dc-title">{{ $letter->title }}</div>
        {{-- Пустой при незаполненном подзаголовке — держит «Открыть документ» у нижнего края --}}
        <div class="dc-sub">{{ $letter->subtitle }}</div>
        <div class="dc-open">Открыть документ →</div>

      </a>

      {{-- Скрытые ссылки для постраничной галереи письма --}}
      @foreach($gallery as $gimg)
        @php $gsrc = is_array($gimg) ? ($gimg['image'] ?? null) : $gimg; @endphp
        @if($gsrc)
          <a href="{{ Storage::url($gsrc) }}" data-fancybox="{{ $groupId }}" style="display:none"></a>
        @endif
      @endforeach
      @endforeach
    </div>

    <div class="reviews-more">
      <a href="{{ route('response') }}" class="btn-outline-dark">Все отзывы</a>
    </div>
  </div>
</section>
@endif
