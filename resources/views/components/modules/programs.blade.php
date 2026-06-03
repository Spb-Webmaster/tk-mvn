@props(
    ['class' => '']
)
@if($categories->isNotEmpty())
<section class="{{ $class }} home-programs home-section" id="programs">
  <div class="container">
    <div class="section-head">
      <div>
        @if(!empty($eyebrow))
        <div class="s-eye">{{ $eyebrow }}</div>
        @endif
        @if(!empty($title))
        <{{ $headingTag }} class="section-title">{{ $title }}</{{ $headingTag }}>
        @endif
      </div>
      @if(!empty($lead))
      <p class="section-lead">{{ $lead }}</p>
      @endif
    </div>
    <div class="programs-grid">
      @foreach($categories as $index => $category)
      <a href="{{ route('training.category.show', $category->slug) }}" class="program-card card-hover">
        <div class="program-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
        <div class="program-icon">{!! $icons[$index] ?? '' !!}</div>
        <div class="program-title">{{ $category->title }}</div>
        @if($category->short_description)
        <div class="program-desc">{!! $category->short_description !!}</div>
        @endif
        <span class="program-link">Подробнее →</span>
      </a>
      @endforeach
    </div>
  </div>
</section>
@endif
