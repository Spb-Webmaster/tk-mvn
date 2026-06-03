@props(['items' => [
    ['num' => '16',   'label' => 'лет на рынке'],
    ['num' => '500+', 'label' => 'тренингов проведено'],
    ['num' => '80+',  'label' => 'компаний-клиентов'],
    ['num' => '12',   'label' => 'оптимальный состав группы'],
]])

<div class="home-stats">
  <div class="stats-inner">
    @foreach($items as $item)
    <div class="stat-item">
      <div class="stat-num">{{ $item['num'] }}</div>
      <div class="stat-label">{{ $item['label'] }}</div>
    </div>
    @endforeach
  </div>
</div>
