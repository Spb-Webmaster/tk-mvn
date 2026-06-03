@props([
    'eyebrow'  => '',
    'title'    => '',
    'subtitle' => '',
    'desc'     => '',
])

<section class="home-hero" id="top">
  <div class="hero-bg" style="background-image: url('{{ asset('storage/images/home/back_trener24.jpg') }}');"></div>
  <div class="hero-inner">
    <div class="hero-content">
      @if(!empty($eyebrow))
      <div class="hero-eyebrow eyebrow">{{ $eyebrow }}</div>
      @endif
      @if(!empty($title))
      <h1 class="hero-title">{!! $title !!}</h1>
      @endif
      @if(!empty($subtitle))
      <p class="hero-subtitle">{{ $subtitle }}</p>
      @endif
      @if(!empty($desc))
      <p class="hero-desc">{{ $desc }}</p>
      @endif
      <div class="hero-actions">
        <button type="button" class="btn-primary open-fancybox" data-form="zapros">Обсудить задачу</button>
        <a href="#programs" class="btn-outline">Наши программы</a>
      </div>
    </div>
  </div>
</section>
