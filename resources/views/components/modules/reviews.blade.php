@props(['reviews' => collect()])

@if($reviews->isNotEmpty())
<section class="home-reviews home-section" id="reviews">
  <div class="container">
    <div class="section-head">
      <div class="s-eye">Отзывы</div>
      <h2 class="section-title">Что говорят участники</h2>
      <p class="section-lead">Руководители и специалисты — о работе на тренингах и результатах в практике переговоров.</p>
    </div>

    <div class="reviews-grid">
      @foreach($reviews as $review)
      <div class="review-card card-hover">
        <span class="review-quote">«</span>
        <div class="review-text">{{ \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($review->desc)), 320) }}</div>
        <div class="review-author">
          <div class="review-author__name">{{ strip_tags(html_entity_decode($review->short_desc ?: $review->title)) }}</div>
          @if($review->subtitle)
          <div class="review-author__role">{{ $review->subtitle }}</div>
          @endif
        </div>
      </div>
      @endforeach
    </div>

    <div class="reviews-more">
      <a href="{{ route('response') }}" class="btn-outline-dark">Все отзывы</a>
    </div>
  </div>
</section>
@endif
