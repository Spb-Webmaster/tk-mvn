{{-- Цифры задаются в админке: Константы → Статистика.
     Значений по умолчанию здесь нет намеренно: иначе на страницах, где
     данные забыли передать, показывались бы зашитые в коде числа,
     расходящиеся с админкой. --}}
@props(['items' => []])

@php
    $items = collect($items)->filter(fn($i) => trim($i['num'] ?? '') !== '')->values();
@endphp

@if($items->isNotEmpty())
<div class="home-stats">
  <div class="stats-inner">
    @foreach($items as $item)
    <div class="stat-item">
      <div class="stat-num">{{ $item['num'] }}</div>
      @if(!empty($item['label']))
      <div class="stat-label">{{ $item['label'] }}</div>
      @endif
    </div>
    @endforeach
  </div>
</div>
@endif
