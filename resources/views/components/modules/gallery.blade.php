@props(['images' => [
    ['src' => 'storage/images/home/07.jpg', 'alt' => 'Тренинг'],
    ['src' => 'storage/images/home/02.jpg', 'alt' => 'Тренинг Газпром Нефть'],
    ['src' => 'storage/images/home/01.jpg', 'alt' => 'Турнир переговорщиков'],
]])

<div class="home-gallery">
  <div class="gallery-grid">
    @foreach($images as $image)
    <div class="gallery-item">
      <img src="{{ asset($image['src']) }}" alt="{{ $image['alt'] }}">
    </div>
    @endforeach
  </div>
</div>
