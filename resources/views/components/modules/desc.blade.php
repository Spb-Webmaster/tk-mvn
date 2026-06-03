@props(['content' => ''])

@if(!empty($content))
<section class="home-desc-section">
  <div class="container">
    <div class="desc">
      {!! $content !!}
    </div>
  </div>
</section>
@endif
